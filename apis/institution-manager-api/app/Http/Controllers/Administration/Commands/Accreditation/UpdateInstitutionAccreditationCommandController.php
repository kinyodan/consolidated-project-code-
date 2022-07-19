<?php
namespace App\Http\Controllers\Administration\Commands\Accreditation;

use App\Http\Controllers\Administration\Commands\ValidateImageUpload;
use App\Http\Controllers\Administration\InstitutionController;
use App\Http\Controllers\CraydelTypes\CraydelJSONResponseType;
use App\Http\Controllers\Helpers\CraydelHelperFunctions;
use App\Http\Controllers\Helpers\CraydelInternalResponseHelper;
use App\Http\Controllers\Helpers\GetLoggedIUserHelper;
use App\Http\Controllers\Helpers\LanguageTranslationHelper;
use App\Http\Controllers\Traits\CanLog;
use App\Jobs\DeleteImagesFromTheCDN;
use App\Jobs\UploadInstitutionAccreditationImageToCDNJob;
use App\Models\Institution;
use App\Models\InstitutionAccreditation;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\DB;
use Respect\Validation\Validator as v;

class UpdateInstitutionAccreditationCommandController
{
    use CanLog;

    /**
     * @var InstitutionController
     */
    protected $institutionController;

    /**
     * Constructor
     * @param InstitutionController $institutionController
     */
    public function __construct(InstitutionController $institutionController){
        $this->institutionController = $institutionController;
    }

    /**
     * Validate the institution accreditation
     *
     * @param Request $request
     * @param string|null $institution_code
     * @param int|null $accreditation_id
     * @return CraydelInternalResponseHelper
     */
    protected function validate(Request $request, ?string $institution_code, ?int $accreditation_id): CraydelInternalResponseHelper
    {
        try{
            $user = GetLoggedIUserHelper::getUser($request);
            $institution_code = CraydelHelperFunctions::toCleanString($institution_code);

            if(!v::intVal()->notEmpty()->validate($accreditation_id)){
                throw new Exception(
                    LanguageTranslationHelper::translate('institutions.errors.accreditation.missing_accreditation_id')
                );
            }

            if(!DB::table((new InstitutionAccreditation())->getTable())->where('id', $accreditation_id)->where('institution_code', $institution_code)){
                throw new Exception(
                    LanguageTranslationHelper::translate('institutions.errors.accreditation.invalid_accreditation_id')
                );
            }

            if(!v::stringVal()->notEmpty()->validate($institution_code)){
                throw new Exception(
                    LanguageTranslationHelper::translate('institutions.errors.invalid_institution_code')
                );
            }

            if(!DB::table((new Institution())->getTable())->where('institution_code', $institution_code)->exists()){
                throw new Exception(
                    LanguageTranslationHelper::translate('institutions.errors.invalid_institution_code')
                );
            }

            $organization_name = $request->input('organization_name');
            $organization_acronym = $request->input('organization_acronym');
            $accreditation_description = $request->input('accreditation_description');

            if(!v::stringVal()->notEmpty()->validate($organization_name)){
                throw new Exception(
                    LanguageTranslationHelper::translate('institutions.errors.accreditation.invalid_organization_name')
                );
            }

            $accreditation_name_duplicate = DB::table((new InstitutionAccreditation())->getTable())
                ->where('institution_code', $institution_code)
                ->where('organization_name_slug', CraydelHelperFunctions::slugifyString($organization_name))
                ->where('id', '!=', $accreditation_id)
                ->exists();

            if($accreditation_name_duplicate){
                throw new Exception(
                    LanguageTranslationHelper::translate('institutions.errors.accreditation.duplicate_accreditation_name')
                );
            }

            if(!v::stringVal()->notEmpty()->validate($accreditation_description)){
                throw new Exception(
                    LanguageTranslationHelper::translate('institutions.errors.accreditation.invalid_accreditation_description')
                );
            }

            if(!v::stringVal()->notEmpty()->validate($organization_acronym)){
                throw new Exception(
                    LanguageTranslationHelper::translate('institutions.errors.accreditation.invalid_accreditation_description')
                );
            }

            $file_upload_path = null;

            if($request->file('organization_image')) {
                $file_upload_path = ValidateImageUpload::validate(
                    $request,
                    'organization_image',
                    $this->institutionController->allowedImageMimeTypes
                );
            }

            return $this->institutionController->internalResponse(new CraydelInternalResponseHelper(
                true,
                "Validated.",
                (object)[
                    'staged_file_path' => $file_upload_path,
                    'old_accreditation_images' => InstitutionController::getInstitutionAccreditationImages($accreditation_id),
                    'payload' => [
                        'organization_name_slug' => CraydelHelperFunctions::slugifyString($organization_name),
                        'organization_name' => CraydelHelperFunctions::toCleanString($organization_name),
                        'organization_acronym' => CraydelHelperFunctions::toCleanString($organization_acronym),
                        'temp_image_path' => $file_upload_path,
                        'accreditation_description' => $accreditation_description,
                        'updated_by' => $user->email,
                        'updated_at' => Carbon::now()->toDateTimeString(),
                    ]
                ]
            ));
        }catch (Exception $exception){
            $this->institutionController->logException($exception);

            return $this->institutionController->internalResponse(new CraydelInternalResponseHelper(false, $exception->getMessage()));
        }
    }

    /**
     * Update accreditation to the institution
     *
     * @param Request $request
     * @param string|null $institution_code
     * @param int|null $accreditation_id
     * @return JsonResponse
     */
    public function update(Request $request, ?string $institution_code, ?int $accreditation_id): JsonResponse
    {
        try {
            $validation = $this->validate($request, $institution_code, $accreditation_id);

            if (!$validation->status) {
                return $this->institutionController->respondInJSON(new CraydelJSONResponseType(false, $validation->message));
            }

            if (!isset($validation->data->payload) || !is_array($validation->data->payload)) {
                return $this->institutionController->respondInJSON(new CraydelJSONResponseType(
                    false,
                    LanguageTranslationHelper::translate('institutions.errors.accreditation.error_updating_institution_accreditation')
                ));
            }

            $accreditation_id = DB::table((new InstitutionAccreditation())->getTable())
                ->where('id', $accreditation_id)
                ->where('institution_code', $institution_code)
                ->update($validation->data->payload);

            if($accreditation_id){
                $accreditation = InstitutionAccreditation::all()
                    ->where('id', $accreditation_id)
                    ->first();

                if(!empty($validation->data->staged_file_path)){
                    dispatch((new DeleteImagesFromTheCDN($validation->data->old_accreditation_images)))->onQueue('delete_images_to_cdn');
                    dispatch((new UploadInstitutionAccreditationImageToCDNJob(
                        $accreditation_id,
                        $accreditation->temp_image_path
                    )))->onQueue('upload_images_to_cdn');
                }

                return $this->institutionController->respondInJSON(new CraydelJSONResponseType(
                    true,
                    LanguageTranslationHelper::translate('institutions.success.accreditation_updated')
                ));
            }else{
                return $this->institutionController->respondInJSON(new CraydelJSONResponseType(
                    false,
                    LanguageTranslationHelper::translate('institutions.errors.accreditation.error_updating_institution_accreditation')
                ));
            }
        }catch (Exception $exception){
            $this->institutionController->logException($exception);
            return $this->institutionController->respondInJSON(new CraydelJSONResponseType(false, $exception->getMessage()));
        }
    }
}
