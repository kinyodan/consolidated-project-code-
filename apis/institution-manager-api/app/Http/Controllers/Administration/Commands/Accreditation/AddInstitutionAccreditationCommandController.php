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
use App\Jobs\UploadInstitutionAccreditationImageToCDNJob;
use App\Models\Institution;
use App\Models\InstitutionAccreditation;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Respect\Validation\Validator;
use Respect\Validation\Validator as v;

class AddInstitutionAccreditationCommandController
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
     * @return CraydelInternalResponseHelper
     */
    protected function validate(Request $request, ?string $institution_code): CraydelInternalResponseHelper
    {
        try{
            $validator = new Validator();
            $user = GetLoggedIUserHelper::getUser($request);
            $institution_code = CraydelHelperFunctions::toCleanString($institution_code);

            if(empty($institution_code)){
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

            if(!$validator::stringVal()->notEmpty()->validate($organization_name)){
                throw new Exception(
                    LanguageTranslationHelper::translate('institutions.errors.accreditation.invalid_organization_name')
                );
            }

            $accreditation_name_duplicate = DB::table((new InstitutionAccreditation())->getTable())
                ->where('institution_code', $institution_code)
                ->where('organization_name_slug', CraydelHelperFunctions::slugifyString($organization_name))
                ->exists();

            if($accreditation_name_duplicate){
                throw new Exception(
                    LanguageTranslationHelper::translate('institutions.errors.accreditation.duplicate_accreditation_name')
                );
            }

            if(!$validator::stringVal()->notEmpty()->validate($accreditation_description)){
                throw new Exception(
                    LanguageTranslationHelper::translate('institutions.errors.accreditation.invalid_accreditation_description')
                );
            }

            if(!$validator::stringVal()->notEmpty()->validate($organization_acronym)){
                throw new Exception(
                    LanguageTranslationHelper::translate('institutions.errors.accreditation.invalid_accreditation_description')
                );
            }

            $file_upload_path = ValidateImageUpload::validate(
                $request,
                'organization_image',
                $this->institutionController->allowedImageMimeTypes
            );

            return $this->institutionController->internalResponse(new CraydelInternalResponseHelper(
                true,
                "Validated.",
                (object)[
                    'staged_file_path' => $file_upload_path,
                    'payload' => [
                        'institution_code' => $institution_code,
                        'organization_name_slug' => CraydelHelperFunctions::slugifyString($organization_name),
                        'organization_name' => CraydelHelperFunctions::toCleanString($organization_name),
                        'organization_acronym' => CraydelHelperFunctions::toCleanString($organization_acronym),
                        'temp_image_path' => $file_upload_path,
                        'accreditation_description' => $accreditation_description,
                        'is_active' => 1,
                        'is_deleted' => 0,
                        'created_by' => $user->email,
                        'updated_by' => $user->email,
                        'created_at' => Carbon::now()->toDateTimeString(),
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
     * Add accreditation to the institution
     *
     * @param Request $request
     * @param string|null $institution_code
     *
     * @return JsonResponse
     */
    public function add(Request $request, ?string $institution_code): JsonResponse
    {
        try {
            $validation = $this->validate($request, $institution_code);

            if (!$validation->status) {
                return $this->institutionController->respondInJSON(new CraydelJSONResponseType(false, $validation->message));
            }

            if (!isset($validation->data->payload) || !is_array($validation->data->payload)) {
                return $this->institutionController->respondInJSON(new CraydelJSONResponseType(
                    false,
                    LanguageTranslationHelper::translate('institutions.errors.accreditation.error_saving_institution_accreditation')
                ));
            }

            $accreditation_id = DB::table((new InstitutionAccreditation())->getTable())
                ->insertGetId($validation->data->payload);

            if($accreditation_id){
                $accreditation = InstitutionAccreditation::all()
                    ->where('id', $accreditation_id)
                    ->first();

                dispatch((new UploadInstitutionAccreditationImageToCDNJob(
                    $accreditation->id,
                    $accreditation->temp_image_path
                )))->onQueue('upload_images_to_cdn');

                return $this->institutionController->respondInJSON(new CraydelJSONResponseType(
                    true,
                    LanguageTranslationHelper::translate('institutions.success.accreditation_saved')
                ));
            }else{
                return $this->institutionController->respondInJSON(new CraydelJSONResponseType(
                    false,
                    LanguageTranslationHelper::translate('institutions.errors.accreditation.error_saving_institution_accreditation')
                ));
            }
        }catch (Exception $exception){
            $this->institutionController->logException($exception);
            return $this->institutionController->respondInJSON(new CraydelJSONResponseType(false, $exception->getMessage()));
        }
    }
}
