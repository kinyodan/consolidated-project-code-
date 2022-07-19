<?php
namespace App\Http\Controllers\Administration\Commands\Alumni;

use App\Http\Controllers\Administration\Commands\ValidateImageUpload;
use App\Http\Controllers\Administration\InstitutionController;
use App\Http\Controllers\CraydelTypes\CraydelJSONResponseType;
use App\Http\Controllers\Helpers\CraydelHelperFunctions;
use App\Http\Controllers\Helpers\CraydelInternalResponseHelper;
use App\Http\Controllers\Helpers\GetLoggedIUserHelper;
use App\Http\Controllers\Helpers\LanguageTranslationHelper;
use App\Http\Controllers\Traits\CanLog;
use App\Jobs\DeleteImagesFromTheCDN;
use App\Jobs\UploadInstitutionAlumnusImageToCDNJob;
use App\Models\Institution;
use App\Models\InstitutionAlumnus;
use App\Models\JobTitle;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Respect\Validation\Validator as v;

class UpdateInstitutionAlumnusCommandController
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
     * @param int|null $alumnus_id
     * @return CraydelInternalResponseHelper
     */
    protected function validate(Request $request, ?string $institution_code, ?int $alumnus_id): CraydelInternalResponseHelper
    {
        try{
            $user = GetLoggedIUserHelper::getUser($request);
            $institution_code = CraydelHelperFunctions::toCleanString($institution_code);

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

            if(!v::intVal()->notEmpty()->validate($alumnus_id)){
                throw new Exception(
                    LanguageTranslationHelper::translate('institutions.errors.alumni.invalid_alumnus_id')
                );
            }

            if(!DB::table((new InstitutionAlumnus())->getTable())->where('id', $alumnus_id)->exists()){
                throw new Exception(
                    LanguageTranslationHelper::translate('institutions.errors.alumni.invalid_alumnus_id')
                );
            }

            $alumni_name = $request->input('alumni_name');
            $graduation_year = $request->input('graduation_year');
            $course_taken = $request->input('course_taken');
            $current_employer = $request->input('current_employer');
            $current_position = $request->input('current_position');
            $personal_profile_url = $request->input('personal_profile_url');

            if(!v::stringVal()->notEmpty()->validate($alumni_name)){
                throw new Exception(
                    LanguageTranslationHelper::translate('institutions.errors.alumni.invalid_alumnus_name')
                );
            }

            $alumnus_name_duplicate = DB::table((new InstitutionAlumnus())->getTable())
                ->where('id', '!=', $alumnus_id)
                ->where('institution_code', $institution_code)
                ->where('alumni_name_slug', CraydelHelperFunctions::slugifyString($alumni_name))
                ->exists();

            if($alumnus_name_duplicate){
                throw new Exception(
                    LanguageTranslationHelper::translate('institutions.errors.alumni.duplicate_alumnus_name')
                );
            }

            if(!v::intVal()->notEmpty()->validate($graduation_year)){
                throw new Exception(
                    LanguageTranslationHelper::translate('institutions.errors.alumni.invalid_graduation_year')
                );
            }

            if(!v::stringVal()->notEmpty()->validate($course_taken)){
                throw new Exception(
                    LanguageTranslationHelper::translate('institutions.errors.alumni.invalid_course_taken')
                );
            }

            if(!v::stringVal()->notEmpty()->validate($current_employer)){
                throw new Exception(
                    LanguageTranslationHelper::translate('institutions.errors.alumni.invalid_current_employer')
                );
            }

            if(!v::intVal()->notEmpty()->validate($current_position)){
                throw new Exception(
                    LanguageTranslationHelper::translate('institutions.errors.alumni.invalid_current_employment_position')
                );
            }

            if(!DB::table((new JobTitle())->getTable())->where('id', $current_position)){
                throw new Exception(
                    LanguageTranslationHelper::translate('institutions.errors.alumni.invalid_current_employment_position')
                );
            }

            if(!v::optional(v::url())->validate($personal_profile_url)){
                throw new Exception(
                    LanguageTranslationHelper::translate('institutions.errors.alumni.invalid_personal_profile_url')
                );
            }

            $file_upload_path = null;

            if($request->file('alumnus_image')) {
                $file_upload_path = ValidateImageUpload::validate(
                    $request,
                    'alumnus_image',
                    $this->institutionController->allowedImageMimeTypes
                );
            }

            return $this->institutionController->internalResponse(new CraydelInternalResponseHelper(
                true,
                "Validated.",
                (object)[
                    'staged_file_path' => $file_upload_path,
                    'old_images' => InstitutionController::getInstitutionAlumnusImages($alumnus_id),
                    'payload' => [
                        'alumni_name_slug' => CraydelHelperFunctions::slugifyString($alumni_name),
                        'alumni_name' => CraydelHelperFunctions::toCleanString($alumni_name),
                        'graduation_year' => CraydelHelperFunctions::toCleanString($graduation_year),
                        'course_taken' => CraydelHelperFunctions::toCleanString($course_taken),
                        'current_employer' => CraydelHelperFunctions::toCleanString($current_employer),
                        'current_position' => CraydelHelperFunctions::toNumbers($current_position),
                        'personal_profile_url' => CraydelHelperFunctions::toCleanString($personal_profile_url),
                        'temp_image_path' => $file_upload_path,
                        'updated_by' => $user->email,
                        'updated_at' => Carbon::now()->toDateTimeString()
                    ]
                ]
            ));
        }catch (Exception $exception){
            $this->institutionController->logException($exception);

            return $this->institutionController->internalResponse(new CraydelInternalResponseHelper(false, $exception->getMessage()));
        }
    }

    /**
     * Update institution alumnus
     *
     * @param Request $request
     * @param string|null $institution_code
     * @param int|null $alumnus_id
     * @return JsonResponse
     */
    public function update(Request $request, ?string $institution_code, ?int $alumnus_id): JsonResponse
    {
        try {
            $validation = $this->validate($request, $institution_code, $alumnus_id);

            if (!$validation->status) {
                return $this->institutionController->respondInJSON(new CraydelJSONResponseType(false, $validation->message));
            }

            if (!isset($validation->data->payload) || !is_array($validation->data->payload)) {
                return $this->institutionController->respondInJSON(new CraydelJSONResponseType(
                    false,
                    LanguageTranslationHelper::translate('institutions.errors.alumni.error_updating_alumnus')
                ));
            }

            $updated = DB::table((new InstitutionAlumnus())->getTable())
                ->where('id', $alumnus_id)
                ->where('institution_code', $institution_code)
                ->update($validation->data->payload);

            if($updated){
                $alumnus = InstitutionAlumnus::all()
                    ->where('id', $alumnus_id)
                    ->first();

                if(!empty($validation->data->staged_file_path)){
                    dispatch((new DeleteImagesFromTheCDN($validation->data->old_images)))->onQueue('delete_images_to_cdn');
                    dispatch((new UploadInstitutionAlumnusImageToCDNJob(
                        $alumnus_id,
                        $alumnus->temp_image_path
                    )))->onQueue('upload_images_to_cdn');
                }

                return $this->institutionController->respondInJSON(new CraydelJSONResponseType(
                    true,
                    LanguageTranslationHelper::translate('institutions.success.alumnus_update')
                ));
            }else{
                return $this->institutionController->respondInJSON(new CraydelJSONResponseType(
                    false,-
                    LanguageTranslationHelper::translate('institutions.errors.alumni.error_updating_alumnus')
                ));
            }
        }catch (Exception $exception){
            $this->institutionController->logException($exception);
            return $this->institutionController->respondInJSON(new CraydelJSONResponseType(false, $exception->getMessage()));
        }
    }
}
