<?php
namespace App\Http\Controllers\Administration\Commands;

use App\Events\InstitutionCreatedEvent;
use App\Http\Controllers\CraydelTypes\CraydelJSONResponseType;
use App\Http\Controllers\Helpers\CraydelHelperFunctions;
use App\Http\Controllers\Helpers\CraydelInternalResponseHelper;
use App\Http\Controllers\Helpers\LanguageTranslationHelper;
use App\Http\Controllers\Administration\InstitutionController;
use App\Models\Institution;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Intervention\Image\ImageManager;
use Respect\Validation\Validator as v;

class CreateInstitutionCommandController
{
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
     * Create a new institution
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function create(Request $request): JsonResponse
    {
        try{
            $validate = $this->validate($request);

            if(!$validate->status){
                return $this->institutionController->respondInJSON((new CraydelJSONResponseType(
                    $validate->status,
                    $validate->message
                )));
            }

            $country_code = $request->get('country_code');
            $institution_type = $request->get('institution_type');
            $ownership_type = $request->get('ownership_type');
            $institution_name = $request->get('institution_name');
            $description = $request->get('description');
            $profile_details = $request->get('profile_details');
            $is_deleted = $request->get('is_deleted');
            $is_active = $request->get('is_active');
            $city = $request->get('city');
            $email_address = $request->get('email_address');
            $academic_office_phone_number = $request->get('academic_office_phone_number');
            $academic_office_email_address = $request->get('academic_office_email_address');
            $academic_office_postal_address = $request->get('academic_office_postal_address');
            $university_postal_address = $request->get('university_postal_address');
            $seo_keywords = $request->get('seo_keywords');
            $seo_description = $request->get('seo_description');
            $system_internal_ranking = $request->get('system_internal_ranking');
            $phone_number = $request->get('phone_number');
            $country_ranking = $request->get('country_ranking');
            $regional_ranking = $request->get('regional_ranking');
            $continental_ranking = $request->get('continental_ranking');
            $global_ranking = $request->get('global_ranking');
            $date_registered = $request->get('date_registered');
            $accredited_by = $request->get('accredited_by');
            $accreditation_body_url = $request->get('accreditation_body_url');
            $accredited_by_acronym = $request->get('accredited_by_acronym');
            $website_url = $request->get('website_url');
            $logo_url = $request->get('logo_url');
            $inquiry_form_url = $request->get('inquiry_form_url');
            $finance_office_phone_number = $request->get('finance_office_phone_number');
            $finance_office_email_address = $request->get('finance_office_email_address');
            $main_campus_physical_location = $request->get('main_campus_physical_location');
            $main_campus_latitude = $request->get('main_campus_latitude');
            $main_campus_longtitude = $request->get('main_campus_longtitude');
            $institution_code = CraydelHelperFunctions::makeRandomString(10, 'INT', false);

            $payload = [
                'country_code' => $country_code,
                'institution_type' => $institution_type,
                'ownership_type' => !empty($ownership_type) ? trim($ownership_type) : null,
                'institution_name_slug' => CraydelHelperFunctions::slugifyString($institution_name),
                'institution_code' => $institution_code,
                'institution_name' => CraydelHelperFunctions::toCleanString($institution_name),
                'description' => CraydelHelperFunctions::toCleanString($description),
                'profile_details' => html_entity_decode($profile_details),
                'is_deleted' => !empty($is_deleted) ? 1 : 0,
                'is_active' => !empty($is_active) ? 1 : 0,
                'city' => !empty($city) ? trim($city) : null,
                'email_address' => !empty($email_address) ? trim($email_address) : null,
                'academic_office_phone_number' => !empty($academic_office_phone_number) ? trim($academic_office_phone_number) : null,
                'academic_office_email_address' => !empty($academic_office_email_address) ? trim($academic_office_email_address) : null,
                'academic_office_postal_address' => !empty($academic_office_postal_address) ? trim($academic_office_postal_address) : null,
                'university_postal_address' => !empty($university_postal_address) ? trim($university_postal_address) : null,
                'seo_keywords' => !empty($seo_keywords) ? trim($seo_keywords) : null,
                'seo_description' => !empty($seo_description) ? trim($seo_description) : null,
                'system_internal_ranking' => !empty($system_internal_ranking) ? trim($system_internal_ranking) : null,
                'phone_number' => !empty($phone_number) ? trim($phone_number) : null,
                'country_ranking' => !empty($country_ranking) ? trim($country_ranking) : null,
                'regional_ranking' => !empty($regional_ranking) ? trim($regional_ranking) : null,
                'continental_ranking' => !empty($continental_ranking) ? trim($continental_ranking) : null,
                'global_ranking' => !empty($global_ranking) ? trim($global_ranking) : null,
                'date_registered' => !empty($date_registered) ? trim($date_registered) : null,
                'accredited_by_acronym' => !empty($accredited_by_acronym) ? trim($accredited_by_acronym) : CraydelHelperFunctions::makeAcronym($accredited_by),
                'accredited_by' => !empty($accredited_by) ? trim($accredited_by) : null,
                'accreditation_body_url' => !empty($accreditation_body_url) ? trim($accreditation_body_url) : null,
                'website_url' => !empty($website_url) ? trim($website_url) : null,
                'logo_url' => !empty($logo_url) ? trim($logo_url) : null,
                'inquiry_form_url' => !empty($inquiry_form_url) ? trim($inquiry_form_url) : null,
                'finance_office_phone_number' => !empty($finance_office_phone_number) ? trim($finance_office_phone_number) : null,
                'finance_office_email_address' => !empty($finance_office_email_address) ? trim($finance_office_email_address) : null,
                'main_campus_physical_location' => !empty($main_campus_physical_location) ? trim($main_campus_physical_location) : null,
                'main_campus_latitude' => !empty($main_campus_latitude) ? trim($main_campus_latitude) : null,
                'main_campus_longtitude' => !empty($main_campus_longtitude) ? trim($main_campus_longtitude) : null,
                'temp_logo_path' => $this->institutionController->stagedFilePath,
                'created_at' => Carbon::now()->toDateTimeString()
            ];

            $hasInserted = false;

            DB::transaction(function () use($payload, &$hasInserted){
                $hasInserted = DB::table((new Institution())->getTable())->insert($payload);
            });

            if($hasInserted){
                event(new InstitutionCreatedEvent($institution_code));

                return $this->institutionController->respondInJSON((new CraydelJSONResponseType(
                    true,
                    LanguageTranslationHelper::translate('institutions.success.created')
                )));
            }else{
                return $this->institutionController->respondInJSON((new CraydelJSONResponseType(
                    false,
                    LanguageTranslationHelper::translate('institutions.errors.error_creating_institution')
                )));
            }
        }catch (Exception $exception){
            $this->institutionController->logException($exception);
            return $this->institutionController->respondInJSON((new CraydelJSONResponseType(
                false,
                LanguageTranslationHelper::translate('institutions.errors.error_creating_institution')
            )));
        }
    }

    /**
     * Validate institution details on update
     * @param Request $request
     *
     * @return CraydelInternalResponseHelper
     */
    protected function validate(Request $request): CraydelInternalResponseHelper
    {
        $country_code = $request->get('country_code');
        $institution_type = $request->get('institution_type');
        $ownership_type = $request->get('ownership_type');
        $institution_name = $request->get('institution_name');
        $description = $request->get('description');
        $profile_details = $request->get('profile_details');

        if(!v::stringVal()->notEmpty()->validate($country_code)){
            return (new CraydelInternalResponseHelper(
                false,
                LanguageTranslationHelper::translate('institutions.errors.missing_country_code')
            ));
        }

        if(!v::intVal()->notEmpty()->validate($institution_type)){
            return (new CraydelInternalResponseHelper(
                false,
                LanguageTranslationHelper::translate('institutions.errors.missing_institution_type')
            ));
        }

        if(!v::stringVal()->notEmpty()->validate($ownership_type)){
            return (new CraydelInternalResponseHelper(
                false,
                LanguageTranslationHelper::translate('institutions.errors.missing_institution_ownership_type')
            ));
        }

        if(!v::stringVal()->notEmpty()->validate($institution_name)){
            return (new CraydelInternalResponseHelper(
                false,
                LanguageTranslationHelper::translate('institutions.errors.missing_institution_name')
            ));
        }

        if(DB::table((new Institution())->getTable())->where('country_code', CraydelHelperFunctions::toCleanString($country_code))->where('institution_name_slug', CraydelHelperFunctions::slugifyString($institution_name))->exists()){
            return (new CraydelInternalResponseHelper(
                false,
                LanguageTranslationHelper::translate('institutions.errors.duplicate_institution_name')
            ));
        }

        if(!v::optional(v::stringVal())->validate($description)){
            return (new CraydelInternalResponseHelper(
                false,
                LanguageTranslationHelper::translate('institutions.errors.invalid_description')
            ));
        }

        if(!v::optional(v::stringVal())->validate($profile_details)){
            return (new CraydelInternalResponseHelper(
                false,
                LanguageTranslationHelper::translate('institutions.errors.invalid_profile_details')
            ));
        }

        if($request->file('institution_logo')) {
            $institution_logo = $request->file('institution_logo');
            $file_mime_type = $institution_logo->getClientMimeType();
            $file_mime_type = CraydelHelperFunctions::toCleanString(strtolower($file_mime_type));

            if (!in_array($file_mime_type, $this->institutionController->allowedImageMimeTypes)) {
                return (new CraydelInternalResponseHelper(
                    false,
                    sprintf(
                        LanguageTranslationHelper::translate('institutions.errors.invalid_logo_file_type'),
                        implode('', $this->institutionController->allowedImageMimeTypes)
                    )
                ));
            }

            $institution_logo_size = $institution_logo->getSize();
            $file_size_in_mbs = CraydelHelperFunctions::convertBytesToMBs($institution_logo_size);

            if(isset($file_size_in_mbs)){
                $maximum_allowed = config('craydle.security.maximum_uploaded_file_size', 10);

                if(floatval($file_size_in_mbs) > $maximum_allowed){
                    return (new CraydelInternalResponseHelper(
                        false,
                        sprintf(
                            LanguageTranslationHelper::translate('institutions.errors.institution_logo_file_size_too_big'),
                            $file_size_in_mbs.'MBs'
                        )
                    ));
                }
            }else{
                return (new CraydelInternalResponseHelper(
                    false,
                    LanguageTranslationHelper::translate('institutions.errors.unable_to_get_the_logo_size')
                ));
            }

            $staged_files_path = storage_path().DIRECTORY_SEPARATOR.'staged-images'.DIRECTORY_SEPARATOR.'institutions-logos'.DIRECTORY_SEPARATOR;
            $institution_logo_name = md5(CraydelHelperFunctions::makeRandomString(20)).'.'.$institution_logo->getClientOriginalExtension();
            $institution_logo->move($staged_files_path, $institution_logo_name);
            $file_upload_path = $staged_files_path.$institution_logo_name;

            if(file_exists($file_upload_path)){
                $manager = new ImageManager();
                $image = $manager->make($file_upload_path)->orientate()->save($file_upload_path);

                $image_width = $image->getWidth();
                $image_height = $image->getHeight();
                $minimum_width = config('craydle.minimum_width');
                $minimum_height = config('craydle.minimum_height');

                if($image_width < $minimum_width){
                    @unlink($file_upload_path);
                    return (new CraydelInternalResponseHelper(
                        false,
                        sprintf(
                            LanguageTranslationHelper::translate('institutions.errors.logo_should_below_minimum_width'),
                            $minimum_width
                        )
                    ));
                }else{
                    if($image_height < $minimum_height){
                        @unlink($file_upload_path);
                        return (new CraydelInternalResponseHelper(
                            false,
                            sprintf(
                                LanguageTranslationHelper::translate('institutions.errors.logo_should_below_minimum_height'),
                                $minimum_height
                            )
                        ));
                    }
                }

                $minimum_aspect_ration_multiplier = config('craydle.logos.allowed_aspect_ration_minimum_multiplier');
                $maximum_aspect_ration_multiplier = config('craydle.logos.allowed_aspect_ration_maximum_multiplier');

                $aspect_ration_multiplier = CraydelHelperFunctions::imageAspectRationMultiplier($image_width,$image_height);

                if(!(($aspect_ration_multiplier >= $minimum_aspect_ration_multiplier) && ($aspect_ration_multiplier <= $maximum_aspect_ration_multiplier))){
                    @unlink($file_upload_path);

                    return (new CraydelInternalResponseHelper(
                        false,
                        LanguageTranslationHelper::translate('institutions.errors.logo_have_an_invalid_aspect_ration')
                    ));
                }
            }

            $this->institutionController->stagedFilePath = $file_upload_path;
        }

        return (new CraydelInternalResponseHelper(
            true,
            LanguageTranslationHelper::translate('institutions.success.validated')
        ));
    }
}
