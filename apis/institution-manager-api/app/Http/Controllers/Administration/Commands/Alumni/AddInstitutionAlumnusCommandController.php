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
use App\Jobs\UploadInstitutionAlumnusImageToCDNJob;
use App\Models\Institution;
use App\Models\InstitutionAlumnus;
use App\Models\JobTitle;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Respect\Validation\Validator;
use Respect\Validation\Validator as v;

class AddInstitutionAlumnusCommandController
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
            $user = GetLoggedIUserHelper::getUser($request);
            $institution_code = CraydelHelperFunctions::toCleanString($institution_code);
            $validator = new Validator();

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

            $alumni_name = $request->input('alumni_name');
            $graduation_year = $request->input('graduation_year');
            $course_taken = $request->input('course_taken');
            $current_employer = $request->input('current_employer');
            $current_position = $request->input('current_position');
            $personal_profile_url = $request->input('personal_profile_url');

            if(!$validator->notEmpty()->validate($alumni_name)){
                throw new Exception(
                    LanguageTranslationHelper::translate('institutions.errors.alumni.invalid_alumnus_name')
                );
            }

            $alumnus_name_duplicate = DB::table((new InstitutionAlumnus())->getTable())
                ->where('institution_code', $institution_code)
                ->where('alumni_name_slug', CraydelHelperFunctions::slugifyString($alumni_name))
                ->exists();

            if($alumnus_name_duplicate){
                throw new Exception(
                    LanguageTranslationHelper::translate('institutions.errors.alumni.duplicate_alumnus_name')
                );
            }

            if(!$validator->notEmpty()->validate($graduation_year)){
                throw new Exception(
                    LanguageTranslationHelper::translate('institutions.errors.alumni.invalid_graduation_year')
                );
            }

            if(!$validator->notEmpty()->validate($course_taken)){
                throw new Exception(
                    LanguageTranslationHelper::translate('institutions.errors.alumni.invalid_course_taken')
                );
            }

            if(!$validator->notEmpty()->validate($current_employer)){
                throw new Exception(
                    LanguageTranslationHelper::translate('institutions.errors.alumni.invalid_current_employer')
                );
            }

            if(!$validator->notEmpty()->validate($current_position)){
                throw new Exception(
                    LanguageTranslationHelper::translate('institutions.errors.alumni.invalid_current_employment_position')
                );
            }

            if(!DB::table((new JobTitle())->getTable())->where('id', $current_position)){
                throw new Exception(
                    LanguageTranslationHelper::translate('institutions.errors.alumni.invalid_current_employment_position')
                );
            }

            if(!$validator::optional($validator::url())->validate($personal_profile_url)){
                throw new Exception(
                    LanguageTranslationHelper::translate('institutions.errors.alumni.invalid_personal_profile_url')
                );
            }

            $file_upload_path = ValidateImageUpload::validate(
                $request,
                'alumnus_image',
                $this->institutionController->allowedImageMimeTypes
            );

            return $this->institutionController->internalResponse(new CraydelInternalResponseHelper(
                true,
                "Validated.",
                (object)[
                    'staged_file_path' => $file_upload_path,
                    'payload' => [
                        'institution_code' => $institution_code,
                        'alumni_name_slug' => CraydelHelperFunctions::slugifyString($alumni_name),
                        'alumni_name' => CraydelHelperFunctions::toCleanString($alumni_name),
                        'graduation_year' => CraydelHelperFunctions::toCleanString($graduation_year),
                        'course_taken' => CraydelHelperFunctions::toCleanString($course_taken),
                        'current_employer' => CraydelHelperFunctions::toCleanString($current_employer),
                        'current_position' => CraydelHelperFunctions::toNumbers($current_position),
                        'personal_profile_url' => CraydelHelperFunctions::toCleanString($personal_profile_url),
                        'temp_image_path' => $file_upload_path,
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
                    LanguageTranslationHelper::translate('institutions.errors.alumni.error_saving_alumnus')
                ));
            }

            $alumnus_id = DB::table((new InstitutionAlumnus())->getTable())
                ->insertGetId($validation->data->payload);

            if($alumnus_id){
                $accreditation = InstitutionAlumnus::all()
                    ->where('id', $alumnus_id)
                    ->first();

                dispatch((new UploadInstitutionAlumnusImageToCDNJob(
                    $accreditation->id,
                    $accreditation->organization_image
                )))->onQueue('upload_images_to_cdn');

                return $this->institutionController->respondInJSON(new CraydelJSONResponseType(
                    true,
                    LanguageTranslationHelper::translate('institutions.success.alumnus_saved')
                ));
            }else{
                return $this->institutionController->respondInJSON(new CraydelJSONResponseType(
                    false,
                    LanguageTranslationHelper::translate('institutions.errors.alumni.error_saving_alumnus')
                ));
            }
        }catch (Exception $exception){
            $this->institutionController->logException($exception);
            return $this->institutionController->respondInJSON(new CraydelJSONResponseType(false, $exception->getMessage()));
        }
    }
}
