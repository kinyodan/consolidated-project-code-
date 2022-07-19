<?php
namespace App\Http\Controllers\Courses\Commands;

use App\Http\Controllers\Helpers\CraydelHelperFunctions;
use App\Http\Controllers\Helpers\CraydelInternalResponseHelper;
use App\Http\Controllers\Helpers\GetLoggedIUserHelper;
use App\Http\Controllers\Helpers\LanguageTranslationHelper;
use App\Http\Controllers\Traits\CanRespond;
use App\Models\AcademicDiscipline;
use App\Models\Course;
use App\Models\LearningMode;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Intervention\Image\ImageManager;
use Respect\Validation\Validator;
use App\Http\Controllers\Helpers\ImmutableOptions;

class ValidateCourseDetails
{
    use CanRespond;

    /**
     * Validation type
    */
    const ON_INSERT = 'VALIDATE_ON_INSERT';
    const ON_UPDATE = 'VALIDATE_ON_UPDATE';

    /**
     * @var string|null $validationType
    */
    protected ?string $validationType = self::ON_INSERT;

    /**
     * @var bool $hasError
    */
    protected bool $hasError = false;

    /**
     * @var string|null $errorMessage
    */
    protected ?string $errorMessage = null;

    /**
     * @var string|null $course_code
    */
    protected ?string $course_code;

    /**
     * Generate the validation error response
     *
     * @var object $validationErrorResponse
     */
    protected object $validationErrorResponse;

    /**
     * @var array $allowedImageMimeTypes
     */
    public array $allowedImageMimeTypes = [
        'image/png', 'image/jpeg',
        'application/octet-stream'
    ];

    /**
     * Staged images
     *
     * @var mixed $staged_images
    */
    public $staged_images;

    /**
     * @var array $graduate_levels
    */
    protected static array $graduate_levels = [
        'Examination',
        'Certificate',
        'Diploma',
        'Degree',
        'Associate Degree',
        'Pathway Programme',
        'Foundation Programme',
    ];

    /**
     * @var array $attendance_type
    */
    protected static array $attendance_type = [
        'Self Paced', 'Full Time',
        'Evening Classes', 'Part Time'
    ];

    /**
     * @var array $standard_fee_billing_type
    */
    protected static array $standard_fee_billing_type = [
        'Total Cost', 'Per Term',
        'Per Semester', 'Per Module',
        'Per Year', 'Per Unit',
        'Per Session', 'Per Day'
    ];

    /**
     * @var static $course_duration_category
    */
    protected static $course_duration_category = [
        'Years','Months','Weeks',
        'Days', 'Sessions'
    ];

    /**
     * @var int $maximum_course_duration_allowed
    */
    public static int $maximum_course_duration_allowed = 11;

    /**
     * @var int $minimum_course_duration_allowed
    */
    public static int $minimum_course_duration_allowed = 1;

    /**
     * @var float $minimum_course_fees_allowed
    */
    public static $minimum_course_fees_allowed = 1;

    /**
     * @var float $maximum_course_fees_allowed
    */
    public static $maximum_course_fees_allowed = 1000000000;

    /**
     * @return array
     */
    public static function getGraduateLevels(): array
    {
        $_graduate_levels = self::$graduate_levels;
        sort($_graduate_levels);

        return $_graduate_levels;
    }

    /**
     * @return array
     */
    public static function getAttendanceType(): array
    {
        $_attendance_type = self::$attendance_type;
        sort($_attendance_type);

        return $_attendance_type;
    }

    /**
     * @return array
     */
    public static function getStandardFeeBillingType(): array
    {
        $_standard_fee_billing_type = self::$standard_fee_billing_type;
        sort($_standard_fee_billing_type);

        return $_standard_fee_billing_type;
    }

    /**
     * @return self
     */
    public static function getCourseDurationCategory()
    {
        $_course_duration_category = self::$course_duration_category;
        sort($_course_duration_category);

        return $_course_duration_category;
    }

    /**
     * Validate course details
     *
     * @param ImmutableOptions $params
     * @param string $type
     * @param Request|null $request
     * @param string|null $course_code
     *
     * @return CraydelInternalResponseHelper
     */
    public function validate(ImmutableOptions $params,  ?Request $request = null, ?string $course_code = null, string $type = self::ON_INSERT): CraydelInternalResponseHelper
    {
        try{
            $this->validationType = $type;
            $this->course_code = $course_code;
            $country_code = $params->get('country_code');
            $institution_code = $params->get('institution_code');
            $course_name = $params->get('course_name');
            $description = $params->get('description');
            $course_overview = $params->get('course_overview');
            $linked_course_categories = $params->get('linked_course_categories');
            $course_type = $params->get('course_type');
            $graduate_level = $params->get('graduate_level');
            $institution_course_code = $params->get('institution_course_code');
            $attendance_type = $params->get('attendance_type');
            $learning_mode = $params->get('learning_mode');
            $faculty_code = $params->get('faculty_code');
            $institution_website_course_url = $params->get('institution_website_course_url');
            $institution_website_application_form_url = $params->get('institution_website_application_form_url');
            $course_requirements = $params->get('course_requirements');
            $enrollment_details = $params->get('enrollment_details');
            $standard_fee_billing_type = $params->get('standard_fee_billing_type');
            $standard_fee_payable = $params->get('standard_fee_payable');
            $standard_fee_breakdown = $params->get('standard_fee_breakdown');
            $foreign_student_fee_billing_type = $params->get('foreign_student_fee_billing_type');
            $foreign_student_fee_payable = $params->get('foreign_student_fee_payable');
            $foreign_student_fee_breakdown = $params->get('foreign_student_fee_breakdown');
            $course_structure_breakdown = $params->get('course_structure_breakdown');
            $course_duration = $params->get('course_duration');
            $course_duration_category = $params->get('course_duration_category');
            $maximum_scholarship_available = $params->get('maximum_scholarship_available');
            $accredited_by = $params->get('accredited_by');
            $accredited_by_acronym = $params->get('accredited_by_acronym');
            $accreditation_organization_url = $params->get('accreditation_organization_url');
            $meta_keywords = $params->get('meta_keywords');
            $meta_description = $params->get('meta_description');
            $currency = $params->get('currency');
            $fees_structure_url = $params->get('fees_structure_url');
            $user = GetLoggedIUserHelper::getUser($request);
            $standard_first_year_fee_payable_usd = floatval($params->get('standard_first_year_fee_payable_usd'));
            $foreign_student_first_year_fee_payable_usd = floatval($params->get('foreign_student_first_year_fee_payable_usd'));
            $ignore_first_year_fees_compute_based_on_total = $params->get('ignore_first_year_fees_compute_based_on_total');
            $linked_blog_articles = $params->get('linked_blog_articles');

            if($this->validateCountryCode($country_code)->hasErrors()){
                return $this->getValidationErrorResponse();
            }

            if($type == self::ON_UPDATE){
                if(empty($course_code)){
                    return new CraydelInternalResponseHelper(
                        false,
                        LanguageTranslationHelper::translate('courses.errors.invalid_course_code')
                    );
                }

                if(!DB::table((new Course())->getTable())->where('course_code', trim($course_code))->exists()){
                    return new CraydelInternalResponseHelper(
                        false,
                        LanguageTranslationHelper::translate('courses.errors.invalid_course_code')
                    );
                }
            }

            if($this->validateInstitutionCode($institution_code)->hasErrors()){
                return $this->getValidationErrorResponse();
            }

            if($this->validateCourseName($course_name, $institution_code, $graduate_level)->hasErrors()){
                return $this->getValidationErrorResponse();
            }

            if($this->validateCourseDescription($description)->hasErrors()){
                return $this->getValidationErrorResponse();
            }

            if($this->validateCourseOverview($course_overview)->hasErrors()){
                return $this->getValidationErrorResponse();
            }

            /*if($this->validateAcademicDisciplineCode(intval($discipline_code))->hasErrors()){
                return $this->getValidationErrorResponse();
            }*/

            if($this->validateLinkedCourseCategories($linked_course_categories)->hasErrors()){
                return $this->getValidationErrorResponse();
            }

            if($this->validateCourseType(intval($course_type))->hasErrors()){
                return $this->getValidationErrorResponse();
            }

            if($this->validateCourseGraduateLevel($graduate_level)->hasErrors()){
                return $this->getValidationErrorResponse();
            }

            if($this->validateInstitutionCourseCode($institution_course_code)->hasErrors()){
                return $this->getValidationErrorResponse();
            }

            if($this->validateAttendanceType($attendance_type)->hasErrors()){
                return $this->getValidationErrorResponse();
            }

            if($this->validateLearningMode(intval($learning_mode))->hasErrors()){
                return $this->getValidationErrorResponse();
            }

            if($this->validateFacultyCode($faculty_code)->hasErrors()){
                return $this->getValidationErrorResponse();
            }

            if($this->validateInstitutionWebsiteCourseURL($institution_website_course_url)->hasErrors()){
                return $this->getValidationErrorResponse();
            }

            if($this->validateInstitutionWebsiteApplicationFormURL($institution_website_application_form_url)->hasErrors()){
                return $this->getValidationErrorResponse();
            }

            if($this->validateCourseRequirements($course_requirements)->hasErrors()){
                return $this->getValidationErrorResponse();
            }

            if($this->validateEnrollmentDetails($enrollment_details)->hasErrors()){
                return $this->getValidationErrorResponse();
            }

            if($this->validateStandardFeeBillingType($standard_fee_billing_type)->hasErrors()){
                return $this->getValidationErrorResponse();
            }

            if($this->validateStandardFeePayable($standard_fee_payable)->hasErrors()){
                return $this->getValidationErrorResponse();
            }

            if($this->validateStandardFirstYearFeePayableUSD($standard_first_year_fee_payable_usd)->hasErrors()){
                return $this->getValidationErrorResponse();
            }

            if($this->validateForeignStudentFirstYearFeePayableUSD($foreign_student_first_year_fee_payable_usd)->hasErrors()){
                return $this->getValidationErrorResponse();
            }

            if($this->validateStandardFeeCurrency($currency)->hasErrors()){
                return $this->getValidationErrorResponse();
            }

            if($this->validateStandardFeeBreakdown($standard_fee_breakdown)->hasErrors()){
                return $this->getValidationErrorResponse();
            }

            if($this->validateForeignStudentFeeBillingType($foreign_student_fee_billing_type)->hasErrors()){
                return $this->getValidationErrorResponse();
            }

            if($this->validateForeignStudentFeePayable($foreign_student_fee_payable)->hasErrors()){
                return $this->getValidationErrorResponse();
            }

            if($this->validateInvalidForeignStudentFeeBreakdown($foreign_student_fee_breakdown)->hasErrors()){
                return $this->getValidationErrorResponse();
            }

            if($this->validateCourseStructureBreakdown($course_structure_breakdown)->hasErrors()){
                return $this->getValidationErrorResponse();
            }

            if($this->validateCourseDuration($course_duration)->hasErrors()){
                return $this->getValidationErrorResponse();
            }

            if($this->validateCourseDurationCategory($course_duration_category)->hasErrors()){
                return $this->getValidationErrorResponse();
            }

            if($this->validateMaximumScholarshipAvailable($maximum_scholarship_available)->hasErrors()){
                return $this->getValidationErrorResponse();
            }

            if($this->validateAccreditedBy($accredited_by)->hasErrors()){
                return $this->getValidationErrorResponse();
            }

            if($this->validateAccreditedByAcronym($accredited_by_acronym)->hasErrors()){
                return $this->getValidationErrorResponse();
            }

            if($this->validateAccreditationOrganizationURL($accreditation_organization_url)->hasErrors()){
                return $this->getValidationErrorResponse();
            }

            if($this->validateImage($request, 'course_image')->hasErrors()){
                return $this->getValidationErrorResponse();
            }

            if($this->validateMetaKeyWords($meta_keywords)->hasErrors()){
                return $this->getValidationErrorResponse();
            }

            if($this->validateMetaDescription($meta_description)->hasErrors()){
                return $this->getValidationErrorResponse();
            }

            if($this->validateLinkedBlogArticles($linked_blog_articles)->hasErrors()){
                return $this->getValidationErrorResponse();
            }

            $payload = [
                'country_code' => CraydelHelperFunctions::toCleanString($params->get('country_code')),
                'currency' => CraydelHelperFunctions::toCleanString($currency),
                'institution_code' => $institution_code,
                'course_code' => !empty($course_code) ? CraydelHelperFunctions::toCleanString($course_code) : CraydelHelperFunctions::makeRandomString(10, 'C', false),
                'course_name_slug' => CraydelHelperFunctions::slugifyString($course_name),
                'course_name' => CraydelHelperFunctions::toCleanString($course_name),
                'description' => !CraydelHelperFunctions::isNull(CraydelHelperFunctions::toCleanString($description)) ? CraydelHelperFunctions::toCleanString($description) : null,
                'course_overview' => !CraydelHelperFunctions::isNull(CraydelHelperFunctions::toCleanString($course_overview)) ? CraydelHelperFunctions::toCleanString($course_overview) : null,
                'course_type' => intval($course_type),
                'graduate_level' => !CraydelHelperFunctions::isNull(CraydelHelperFunctions::toCleanString($graduate_level)) ? CraydelHelperFunctions::toCleanString($graduate_level) : null,
                'attendance_type' => !CraydelHelperFunctions::isNull(CraydelHelperFunctions::toCleanString($attendance_type)) ? CraydelHelperFunctions::toCleanString($attendance_type) : null,
                'learning_mode' => !CraydelHelperFunctions::isNull($learning_mode) ? intval($learning_mode) : null,
                'faculty_code' => !CraydelHelperFunctions::isNull(CraydelHelperFunctions::toCleanString($faculty_code)) ? CraydelHelperFunctions::toCleanString($faculty_code) : null,
                'institution_website_course_url' => !CraydelHelperFunctions::isNull(CraydelHelperFunctions::toCleanString($institution_website_course_url)) ? CraydelHelperFunctions::toCleanString($institution_website_course_url) : null,
                'institution_website_application_form_url' => !CraydelHelperFunctions::isNull(CraydelHelperFunctions::toCleanString($institution_website_application_form_url)) ? CraydelHelperFunctions::toCleanString($institution_website_application_form_url) : null,
                'course_requirements' => !CraydelHelperFunctions::isNull(CraydelHelperFunctions::toCleanString($course_requirements)) ? CraydelHelperFunctions::toCleanString($course_requirements) : null,
                'enrollment_details' => !CraydelHelperFunctions::isNull(CraydelHelperFunctions::toCleanString($enrollment_details)) ? CraydelHelperFunctions::toCleanString($enrollment_details) : null,
                'standard_fee_billing_type' => !CraydelHelperFunctions::isNull(CraydelHelperFunctions::toCleanString($standard_fee_billing_type)) ? CraydelHelperFunctions::toCleanString($standard_fee_billing_type) : null,
                'standard_fee_payable' => round(CraydelHelperFunctions::toNumbers($standard_fee_payable), 2),
                'standard_fee_breakdown' => !CraydelHelperFunctions::isNull(CraydelHelperFunctions::toCleanString($standard_fee_breakdown)) ? CraydelHelperFunctions::toCleanString($standard_fee_breakdown) : null,
                'foreign_student_fee_billing_type' => !CraydelHelperFunctions::isNull(CraydelHelperFunctions::toCleanString($foreign_student_fee_billing_type)) ? CraydelHelperFunctions::toCleanString($foreign_student_fee_billing_type) : null,
                'foreign_student_fee_payable' => !CraydelHelperFunctions::isNull($foreign_student_fee_payable) ? round(CraydelHelperFunctions::toNumbers($foreign_student_fee_payable), 2) : null,
                'foreign_student_fee_breakdown' => !CraydelHelperFunctions::isNull(CraydelHelperFunctions::toCleanString($foreign_student_fee_breakdown)) ? CraydelHelperFunctions::toCleanString($foreign_student_fee_breakdown) : null,
                'temp_course_image_url' => isset($this->staged_images['course_image']['uploaded_image_path']) && !is_null($this->staged_images['course_image']['uploaded_image_path']) ? $this->staged_images['course_image']['uploaded_image_path'] : null,
                'course_structure_breakdown' => !CraydelHelperFunctions::isNull(CraydelHelperFunctions::toCleanString($course_structure_breakdown)) ? CraydelHelperFunctions::toCleanString($course_structure_breakdown) : null,
                'course_duration' => intval($course_duration),
                'course_duration_category' => !CraydelHelperFunctions::isNull(CraydelHelperFunctions::toCleanString($course_duration_category)) ? CraydelHelperFunctions::toCleanString($course_duration_category) : null,
                'maximum_scholarship_available' => !CraydelHelperFunctions::isNull($maximum_scholarship_available) ? round(CraydelHelperFunctions::toNumbers($maximum_scholarship_available), 2) : null,
                'accredited_by' => !CraydelHelperFunctions::isNull(CraydelHelperFunctions::toCleanString($accredited_by)) ? CraydelHelperFunctions::toCleanString($accredited_by) : null,
                'accredited_by_acronym' => !CraydelHelperFunctions::isNull(CraydelHelperFunctions::toCleanString($accredited_by_acronym)) ? CraydelHelperFunctions::toCleanString($accredited_by_acronym) : null,
                'accreditation_organization_url' => !CraydelHelperFunctions::isNull(CraydelHelperFunctions::toCleanString($accreditation_organization_url)) ? CraydelHelperFunctions::toCleanString($accreditation_organization_url) : null,
                'meta_keywords' => !CraydelHelperFunctions::isNull(CraydelHelperFunctions::toCleanString($meta_keywords)) ? CraydelHelperFunctions::toCleanString($meta_keywords) : null,
                'meta_description' => !CraydelHelperFunctions::isNull(CraydelHelperFunctions::toCleanString($meta_description)) ? CraydelHelperFunctions::toCleanString($meta_description) : null,
                'fees_structure_url' => !CraydelHelperFunctions::isNull(CraydelHelperFunctions::toCleanString($fees_structure_url)) ?  CraydelHelperFunctions::toCleanString($fees_structure_url) : null,
                'course_rating' => 0,
                'has_updates' => 1,
                'is_picked_for_indexing' => 0,
                'indexing_error' => null,
                'time_taken_to_index' => null,
                'time_picked_for_indexing' => null,
                'standard_first_year_fee_payable_usd' => !CraydelHelperFunctions::isNull($standard_first_year_fee_payable_usd) ? round(CraydelHelperFunctions::toNumbers($standard_first_year_fee_payable_usd), 2) : null,
                'foreign_student_first_year_fee_payable_usd' => !CraydelHelperFunctions::isNull($foreign_student_first_year_fee_payable_usd) ? round(CraydelHelperFunctions::toNumbers($foreign_student_first_year_fee_payable_usd), 2) : null,
                'standard_first_year_fee_payable_usd_is_manual' => !empty($standard_first_year_fee_payable_usd) ? 1 : 0,
                'foreign_student_first_year_fee_payable_usd_is_manual' => !empty($foreign_student_first_year_fee_payable_usd) ? 1 : 0,
                'ignore_first_year_fees_compute_based_on_total' => !empty($ignore_first_year_fees_compute_based_on_total) && intval($ignore_first_year_fees_compute_based_on_total) >= 1 ? 1 : 0,
                'linked_blog_articles' => !CraydelHelperFunctions::isNull($linked_blog_articles) ? $linked_blog_articles : null
            ];

            if($type == self::ON_UPDATE){
                $payload['updated_at'] = Carbon::now()->toDateTime();
                $payload['updated_by'] = isset($user->email) && !empty($user->email) ? $user->email : null;
            }else{
                $payload['created_at'] = Carbon::now()->toDateTime();
                $payload['created_by'] = isset($user->email) && !empty($user->email) ? $user->email : null;
            }

            return $this->getValidationSuccessResponse(
                (object)[
                    'staged_images' => $this->staged_images,
                    'validated_values' => $payload
                ]
            );
        }catch (\Exception $exception){
            $this->logException($exception);

            return new CraydelInternalResponseHelper(
                false,
                $exception->getMessage().' '.$exception->getLine()
            );
        }
    }

    /**
     * @return bool
     */
    public function hasErrors(): bool
    {
        return $this->hasError;
    }

    /**
     * @return CraydelInternalResponseHelper
     */
    public function getValidationErrorResponse(): CraydelInternalResponseHelper
    {
        return new CraydelInternalResponseHelper(
            !$this->hasError,
            $this->errorMessage
        );
    }

    /**
     * @param object|null $result
     * @return CraydelInternalResponseHelper
     *
     */
    public function getValidationSuccessResponse(?object $result = null): CraydelInternalResponseHelper
    {
        return new CraydelInternalResponseHelper(
            !$this->hasError,
            LanguageTranslationHelper::translate('courses.success.validated'),
            $result
        );
    }

    /**
     * Validate country code
     *
     * @param string|null $country_code
     *
     * @return ValidateCourseDetails
     */
    private function validateCountryCode(?string $country_code): ValidateCourseDetails
    {
        if(!(new Validator())::stringVal()->notEmpty()->validate($country_code)){
            $this->hasError = true;
            $this->errorMessage = LanguageTranslationHelper::translate('courses.errors.missing_country_code');
        }else{
            $this->hasError = false;
        }

        return $this;
    }

    /**
     * Validate institution code
     *
     * @param string|null $institution_code
     *
     * @return ValidateCourseDetails
     */
    private function validateInstitutionCode(?string $institution_code): ValidateCourseDetails
    {
        if(!(new Validator())::stringVal()->notEmpty()->validate($institution_code)){
            $this->hasError = true;
            $this->errorMessage = LanguageTranslationHelper::translate('courses.errors.missing_institution_code');
        }else{
            $this->hasError = false;
        }

        return $this;
    }

    /**
     * Validate course name
     *
     * @param string|null $course_name
     * @param string|null $institution_code
     * @param string|null $graduate_level
     *
     * @return ValidateCourseDetails
     */
    private function validateCourseName(?string $course_name, ?string $institution_code, ?string $graduate_level): ValidateCourseDetails
    {
        if(!(new Validator())::stringVal()->notEmpty()->validate($course_name)){
            $this->hasError = true;
            $this->errorMessage = LanguageTranslationHelper::translate('courses.errors.missing_course_name');
        }else{
            $db_query = DB::table((new Course())->getTable())
                ->where('institution_code', $institution_code)
                ->where('course_name_slug', CraydelHelperFunctions::slugifyString($course_name))
                ->where('graduate_level', $graduate_level);

            if($this->validationType == self::ON_INSERT){
                $check_duplicate = $db_query->exists();
            }else{
                $check_duplicate = $db_query
                    ->where('course_code', '!=', $this->course_code)
                    ->exists();
            }

            if($check_duplicate == true){
                $this->hasError = true;
                $this->errorMessage = LanguageTranslationHelper::translate('courses.errors.duplicate_course_name');
            }else{
                $this->hasError = false;
            }
        }

        return $this;
    }

    /**
     * Validate course description
     *
     * @param string|null $description
     *
     * @return ValidateCourseDetails
     */
    private function validateCourseDescription(?string $description): ValidateCourseDetails
    {
        if(!CraydelHelperFunctions::isNull($description) && !(new Validator())::optional((new Validator())::stringVal())->validate($description)){
            $this->hasError = true;
            $this->errorMessage = LanguageTranslationHelper::translate('courses.errors.invalid_description');
        }else{
            $this->hasError = false;
        }

        return $this;
    }

    /**
     * Validate course overview
     *
     * @param string|null $course_overview
     *
     * @return ValidateCourseDetails
     */
    private function validateCourseOverview(?string $course_overview): ValidateCourseDetails
    {
        if(CraydelHelperFunctions::isNull($course_overview) && !(new Validator())::optional((new Validator())::stringVal())->validate($course_overview)){
            $this->hasError = true;
            $this->errorMessage = LanguageTranslationHelper::translate('courses.errors.invalid_course_overview');
        }else{
            $this->hasError = false;
        }
        return $this;
    }

    /**
     * Validate discipline code
     *
     * @param int|null $discipline_id
     *
     * @return ValidateCourseDetails
     */
    private function validateAcademicDisciplineCode(?int $discipline_id): ValidateCourseDetails
    {
        if(!(new Validator())::intVal()->notEmpty()->validate($discipline_id)){
            $this->hasError = true;
            $this->errorMessage = LanguageTranslationHelper::translate('courses.errors.invalid_discipline_code');
        }else{
            if(!DB::table((new AcademicDiscipline())->getTable())->where('id', $discipline_id)->exists()){
                $this->hasError = true;
                $this->errorMessage = LanguageTranslationHelper::translate('courses.errors.invalid_discipline_code');
            }else{
                $this->hasError = false;
            }
        }

        return $this;
    }

    /**
     * Validate course type
     *
     * @param int|null $course_type
     *
     * @return ValidateCourseDetails
     */
    private function validateCourseType(?int $course_type): ValidateCourseDetails
    {
        if(!(new Validator())::intVal()->notEmpty()->validate($course_type)){
            $this->hasError = true;
            $this->errorMessage = LanguageTranslationHelper::translate('courses.errors.missing_course_type');
        }else{
            $this->hasError = false;
        }

        return $this;
    }

    /**
     * Validate course type
     *
     * @param string|null $graduate_level
     *
     * @return ValidateCourseDetails
     */
    private function validateCourseGraduateLevel(?string $graduate_level): ValidateCourseDetails
    {
        if(!(new Validator())::stringVal()->notEmpty()->validate($graduate_level)){
            $this->hasError = true;
            $this->errorMessage = LanguageTranslationHelper::translate('courses.errors.missing_course_graduate_level');
        }else{
            $this->hasError = false;
        }

        return $this;
    }

    /**
     * Validate course type
     *
     * @param string|null $linked_course_categories
     * @return ValidateCourseDetails
     */
    private function validateLinkedCourseCategories(?string $linked_course_categories): ValidateCourseDetails
    {
        if(!(new Validator())::json()->notEmpty()->validate($linked_course_categories)){
            $this->hasError = true;
            $this->errorMessage = LanguageTranslationHelper::translate('courses.errors.missing_linked_course_categories');
        }else{
            $this->hasError = false;
        }

        return $this;
    }

    /**
     * Validate institution course code
     *
     * @param string|null $institution_course_code
     *
     * @return ValidateCourseDetails
     */
    private function validateInstitutionCourseCode(?string $institution_course_code): ValidateCourseDetails
    {
        if(!CraydelHelperFunctions::isNull($institution_course_code) && !(new Validator())::optional((new Validator())::stringVal())->validate($institution_course_code)){
            $this->hasError = true;
            $this->errorMessage = LanguageTranslationHelper::translate('courses.errors.institution_course_code');
        }else{
            $this->hasError = false;
        }

        return $this;
    }

    /**
     * Validate attendance type
     *
     * @param string|null $attendance_type
     *
     * @return ValidateCourseDetails
     */
    private function validateAttendanceType(?string $attendance_type): ValidateCourseDetails
    {
        if(!(new Validator())::stringVal()->notEmpty()->validate($attendance_type)){
            $this->hasError = true;
            $this->errorMessage = LanguageTranslationHelper::translate('courses.errors.invalid_attendance_type');
        }else{
            $this->hasError = false;
        }

        return $this;
    }

    /**
     * Validate learning mode
     *
     * @param int|null $learning_mode_id
     *
     * @return ValidateCourseDetails
     */
    private function validateLearningMode(?int $learning_mode_id): ValidateCourseDetails
    {
        if(!(new Validator())::intVal()->notEmpty()->validate($learning_mode_id)){
            $this->hasError = true;
            $this->errorMessage = LanguageTranslationHelper::translate('courses.errors.invalid_learning_mode');
        }else{
            if(!DB::table((new LearningMode())->getTable())->where('id', $learning_mode_id)->exists()){
                $this->hasError = true;
                $this->errorMessage = LanguageTranslationHelper::translate('courses.errors.invalid_learning_mode');
            }else{
                $this->hasError = false;
            }
        }

        return $this;
    }

    /**
     * Validate faculty code
     *
     * @param string|null $faculty_code
     *
     * @return ValidateCourseDetails
     */
    private function validateFacultyCode(?string $faculty_code): ValidateCourseDetails
    {
        if(!CraydelHelperFunctions::isNull($faculty_code) && !(new Validator())::optional((new Validator())::stringVal())->validate($faculty_code)){
            $this->hasError = true;
            $this->errorMessage = LanguageTranslationHelper::translate('courses.errors.invalid_faculty_code');
        }else{
            $this->hasError = false;
        }

        return $this;
    }

    /**
     * Validate institution website course URL
     *
     * @param string|null $institution_website_course_url
     *
     * @return ValidateCourseDetails
     */
    private function validateInstitutionWebsiteCourseURL(?string $institution_website_course_url): ValidateCourseDetails
    {
        if(!CraydelHelperFunctions::isNull($institution_website_course_url) && !(new Validator())::optional((new Validator())::url())->validate($institution_website_course_url)){
            $this->hasError = true;
            $this->errorMessage = LanguageTranslationHelper::translate('courses.errors.invalid_institution_website_course_url');
        }else{
            $this->hasError = false;
        }

        return $this;
    }

    /**
     * Validate institution website application form URL
     *
     * @param string|null $institution_website_application_form_url
     *
     * @return ValidateCourseDetails
     */
    private function validateInstitutionWebsiteApplicationFormURL(?string $institution_website_application_form_url): ValidateCourseDetails
    {
        if(!CraydelHelperFunctions::isNull($institution_website_application_form_url) && !(new Validator())::optional((new Validator())::url())->validate($institution_website_application_form_url)){
            $this->hasError = true;
            $this->errorMessage = LanguageTranslationHelper::translate('courses.errors.invalid_institution_website_application_form_url');
        }else{
            $this->hasError = false;
        }

        return $this;
    }

    /**
     * Validate course requirements
     *
     * @param string|null $course_requirements
     *
     * @return ValidateCourseDetails
     */
    private function validateCourseRequirements(?string $course_requirements): ValidateCourseDetails
    {
        if(!CraydelHelperFunctions::isNull($course_requirements) && !(new Validator())::optional((new Validator())::stringVal())->validate($course_requirements)){
            $this->hasError = true;
            $this->errorMessage = LanguageTranslationHelper::translate('courses.errors.course_requirements');
        }else{
            $this->hasError = false;
        }

        return $this;
    }

    /**
     * Validate next enrollment details
     *
     * @param string|null $enrollment_details
     *
     * @return ValidateCourseDetails
     */
    private function validateEnrollmentDetails(?string $enrollment_details): ValidateCourseDetails
    {
        if(!CraydelHelperFunctions::isNull($enrollment_details) && !(new Validator())::optional((new Validator())::json())->validate($enrollment_details)){
            $this->hasError = true;
            $this->errorMessage = LanguageTranslationHelper::translate('courses.errors.invalid_enrollment_details');
        }else{
            $this->hasError = false;
        }

        return $this;
    }

    /**
     * Validate standard fee billing type
     *
     * @param string|null $standard_fee_billing_type
     *
     * @return ValidateCourseDetails
     */
    private function validateStandardFeeBillingType(?string $standard_fee_billing_type): ValidateCourseDetails
    {
        if (!(new Validator())::stringVal()->notEmpty()->validate($standard_fee_billing_type)) {
            $this->hasError = true;
            $this->errorMessage = LanguageTranslationHelper::translate('courses.errors.invalid_standard_fee_billing_type');
        } else {
            $this->hasError = false;
        }

        return $this;
    }

    /**
     * Validate standard fee currency
     *
     * @param string|null $currency
     *
     * @return ValidateCourseDetails
     */
    private function validateStandardFeeCurrency(?string $currency): ValidateCourseDetails
    {
        if (!(new Validator())::stringVal()->notEmpty()->validate($currency)) {
            $this->hasError = true;
            $this->errorMessage = LanguageTranslationHelper::translate('courses.errors.invalid_standard_fee_currency');
        } else {
            $this->hasError = false;
        }

        return $this;
    }

    /**
     * Validate standard fee payable
     *
     * @param string|null $standard_fee_payable
     * @return ValidateCourseDetails
     */
    private function validateStandardFeePayable(?string $standard_fee_payable): ValidateCourseDetails
    {
        $standard_fee_payable = CraydelHelperFunctions::toNumbers($standard_fee_payable);

        if (!(new Validator())::floatVal()->length(1,15)->notEmpty()->validate($standard_fee_payable)) {
            $this->hasError = true;
            $this->errorMessage = LanguageTranslationHelper::translate('courses.errors.invalid_standard_fee_payable');
        } else {
            $this->hasError = false;
        }

        return $this;
    }

    /**
     * Validate standard first year fee payable usd
     *
     * @param string|null $standard_first_year_fee_payable_usd
     * @return ValidateCourseDetails
     */
    private function validateStandardFirstYearFeePayableUSD(?string $standard_first_year_fee_payable_usd): ValidateCourseDetails
    {
        if(empty($standard_first_year_fee_payable_usd)){
            $this->hasError = false;
        }else{
            $standard_first_year_fee_payable_usd = CraydelHelperFunctions::toNumbers($standard_first_year_fee_payable_usd);

            if (!(new Validator())::floatVal()->length(1,15)->notEmpty()->validate($standard_first_year_fee_payable_usd)) {
                $this->hasError = true;
                $this->errorMessage = LanguageTranslationHelper::translate('courses.errors.invalid_standard_first_year_fee_payable_usd');
            } else {
                $this->hasError = false;
            }
        }

        return $this;
    }

    /**
     * Validate foreign student first year fee payable USD
     *
     * @param string|null $foreign_student_first_year_fee_payable_usd
     * @return ValidateCourseDetails
     */
    private function validateForeignStudentFirstYearFeePayableUSD(?string $foreign_student_first_year_fee_payable_usd): ValidateCourseDetails
    {
        if(empty($foreign_student_first_year_fee_payable_usd)){
            $this->hasError = false;
        }else{
            $foreign_student_first_year_fee_payable_usd = CraydelHelperFunctions::toNumbers($foreign_student_first_year_fee_payable_usd);

            if (!(new Validator())::floatVal()->length(1,15)->notEmpty()->validate($foreign_student_first_year_fee_payable_usd)) {
                $this->hasError = true;-
                $this->errorMessage = LanguageTranslationHelper::translate('courses.errors.invalid_foreign_student_first_year_fee_payable_usd');
            } else {
                $this->hasError = false;
            }
        }

        return $this;
    }

    /**
     * Validate standard fee breakdown
     *
     * @param string|null $standard_fee_breakdown
     *
     * @return ValidateCourseDetails
     */
    private function validateStandardFeeBreakdown(?string $standard_fee_breakdown): ValidateCourseDetails
    {
        if (!CraydelHelperFunctions::isNull($standard_fee_breakdown) && !(new Validator())::optional((new Validator())::stringVal())->validate($standard_fee_breakdown)) {
            $this->hasError = true;
            $this->errorMessage = LanguageTranslationHelper::translate('courses.errors.invalid_standard_fee_breakdown');
        } else {
            $this->hasError = false;
        }

        return $this;
    }

    /**
     * Validate foreign student fee billing type
     *
     * @param string|null $foreign_student_fee_billing_type
     *
     * @return ValidateCourseDetails
     */
    private function validateForeignStudentFeeBillingType(?string $foreign_student_fee_billing_type): ValidateCourseDetails
    {
        if (!CraydelHelperFunctions::isNull($foreign_student_fee_billing_type) && !(new Validator())::optional((new Validator())::stringVal())->validate($foreign_student_fee_billing_type)) {
            $this->hasError = true;
            $this->errorMessage = LanguageTranslationHelper::translate('courses.errors.invalid_foreign_student_fee_billing_type');
        } else {
            $this->hasError = false;
        }

        return $this;
    }

    /**
     * Validate foreign student fee payable
     *
     * @param string|null $foreign_student_fee_payable
     *
     * @return ValidateCourseDetails
     */
    private function validateForeignStudentFeePayable(?string $foreign_student_fee_payable): ValidateCourseDetails
    {
        $foreign_student_fee_payable = CraydelHelperFunctions::toNumbers($foreign_student_fee_payable);

        if (!CraydelHelperFunctions::isNull($foreign_student_fee_payable) && !(new Validator())::optional((new Validator())::floatVal())->min(1)->validate($foreign_student_fee_payable)) {
            $this->hasError = true;
            $this->errorMessage = LanguageTranslationHelper::translate('courses.errors.invalid_foreign_student_fee_payable');
        } else {
            $this->hasError = false;
        }

        return $this;
    }

    /**
     * Validate foreign student fee breakdown
     *
     * @param string|null $invalid_foreign_student_fee_breakdown
     *
     * @return ValidateCourseDetails
     */
    private function validateInvalidForeignStudentFeeBreakdown(?string $invalid_foreign_student_fee_breakdown): ValidateCourseDetails
    {
        if (!CraydelHelperFunctions::isNull($invalid_foreign_student_fee_breakdown) && !(new Validator())::optional((new Validator())::stringVal())->validate($invalid_foreign_student_fee_breakdown)) {
            $this->hasError = true;
            $this->errorMessage = LanguageTranslationHelper::translate('courses.errors.invalid_foreign_student_fee_breakdown');
        } else {
            $this->hasError = false;
        }

        return $this;
    }

    /**
     * Validate course structure breakdown
     *
     * @param string|null $course_structure_breakdown
     *
     * @return ValidateCourseDetails
     */
    private function validateCourseStructureBreakdown(?string $course_structure_breakdown): ValidateCourseDetails
    {
        if (!CraydelHelperFunctions::isNull($course_structure_breakdown) && !(new Validator())::optional((new Validator())::stringVal())->validate($course_structure_breakdown)) {
            $this->hasError = true;
            $this->errorMessage = LanguageTranslationHelper::translate('courses.errors.invalid_course_structure_breakdown');
        } else {
            $this->hasError = false;
        }

        return $this;
    }

    /**
     * Validate course duration
     *
     * @param string|null $course_duration
     *
     * @return ValidateCourseDetails
     */
    private function validateCourseDuration(?string $course_duration): ValidateCourseDetails
    {
        if (!(new Validator())::floatVal()->notEmpty()->validate($course_duration)) {
            $this->hasError = true;
            $this->errorMessage = LanguageTranslationHelper::translate('courses.errors.invalid_course_duration');
        } else {
            $this->hasError = false;
        }

        return $this;
    }

    /**
     * Validate course duration category
     *
     * @param string|null $course_duration_category
     *
     * @return ValidateCourseDetails
     */
    private function validateCourseDurationCategory(?string $course_duration_category): ValidateCourseDetails
    {
        if (!(new Validator())::stringVal()->notEmpty()->validate($course_duration_category)) {
            $this->hasError = true;
            $this->errorMessage = LanguageTranslationHelper::translate('courses.errors.invalid_course_duration_category');
        } else {
            $this->hasError = false;
        }

        return $this;
    }

    /**
     * Validate maximum scholarship available
     *
     * @param string|null $maximum_scholarship_available
     *
     * @return ValidateCourseDetails
     */
    private function validateMaximumScholarshipAvailable(?string $maximum_scholarship_available): ValidateCourseDetails
    {
        if (!CraydelHelperFunctions::isNull($maximum_scholarship_available) && !(new Validator())::optional((new Validator())::floatVal()->between(0, 100))->validate($maximum_scholarship_available)) {
            $this->hasError = true;
            $this->errorMessage = LanguageTranslationHelper::translate('courses.errors.invalid_maximum_scholarship_available');
        } else {
            $this->hasError = false;
        }

        return $this;
    }

    /**
     * Validate accredited by
     *
     * @param string|null $accredited_by
     *
     * @return ValidateCourseDetails
     */
    private function validateAccreditedBy(?string $accredited_by): ValidateCourseDetails
    {
        if (!CraydelHelperFunctions::isNull($accredited_by) && !(new Validator())::optional((new Validator())::stringVal())->validate($accredited_by)) {
            $this->hasError = true;
            $this->errorMessage = LanguageTranslationHelper::translate('courses.errors.invalid_accredited_by');
        } else {
            $this->hasError = false;
        }

        return $this;
    }

    /**
     * Validate accreditation body acronym
     *
     * @param string|null $accredited_by_acronym
     *
     * @return ValidateCourseDetails
     */
    private function validateAccreditedByAcronym(?string $accredited_by_acronym): ValidateCourseDetails
    {
        if (!CraydelHelperFunctions::isNull($accredited_by_acronym) && !(new Validator())::optional((new Validator())::stringVal())->validate($accredited_by_acronym)) {
            $this->hasError = true;
            $this->errorMessage = LanguageTranslationHelper::translate('courses.errors.invalid_accreditation_body_acronym');
        } else {
            $this->hasError = false;
        }

        return $this;
    }

    /**
     * Validate accreditation organization url
     *
     * @param string|null $accreditation_organization_url
     *
     * @return ValidateCourseDetails
     */
    private function validateAccreditationOrganizationURL(?string $accreditation_organization_url): ValidateCourseDetails
    {
        if (!CraydelHelperFunctions::isNull($accreditation_organization_url) && !(new Validator())::optional((new Validator())::url())->validate($accreditation_organization_url)) {
            $this->hasError = true;
            $this->errorMessage = LanguageTranslationHelper::translate('courses.errors.invalid_accreditation_organization_url');
        } else {
            $this->hasError = false;
        }

        return $this;
    }

    /**
     * Validate course image
     *
     * @param Request $request
     * @param $image_field_name
     * @return ValidateCourseDetails
     */
    private function validateImage(Request $request, $image_field_name): ValidateCourseDetails {
        if($request->file($image_field_name)) {
            $image_field = $request->file($image_field_name);
            $file_mime_type = $image_field->getClientMimeType();
            $file_mime_type = CraydelHelperFunctions::toCleanString(strtolower($file_mime_type));

            if (!in_array($file_mime_type, $this->allowedImageMimeTypes)) {
                $this->hasError = true;
                $this->errorMessage = sprintf(
                    LanguageTranslationHelper::translate('courses.errors.invalid_course_image_file_type'),
                    implode('', $this->allowedImageMimeTypes)
                );
            }

            $institution_logo_size = $image_field->getSize();
            $file_size_in_mbs = CraydelHelperFunctions::convertBytesToMBs($institution_logo_size);

            if(isset($file_size_in_mbs)){
                $maximum_allowed = config('craydle.security.maximum_uploaded_file_size', 10);

                if(floatval($file_size_in_mbs) > $maximum_allowed){
                    $this->hasError = true;
                    $this->errorMessage = sprintf(
                        LanguageTranslationHelper::translate('courses.errors.invalid_course_image_file_size_too_big'),
                        $maximum_allowed
                    );
                }
            }else{
                $this->hasError = true;
                $this->errorMessage = LanguageTranslationHelper::translate('courses.errors.invalid_course_image_file_size_can_not_be_found');
            }

            $staged_files_path = storage_path().DIRECTORY_SEPARATOR.'staged-files'.DIRECTORY_SEPARATOR.'staged-images'.DIRECTORY_SEPARATOR.'course-images'.DIRECTORY_SEPARATOR;
            $institution_logo_name = md5(CraydelHelperFunctions::makeRandomString(20)).'.'.$image_field->getClientOriginalExtension();
            $image_field->move($staged_files_path, $institution_logo_name);
            $file_upload_path = $staged_files_path.$institution_logo_name;

            if(file_exists($file_upload_path)){
                $manager = new ImageManager();
                $image = $manager->make($file_upload_path)->orientate()->save($file_upload_path);

                $image_width = $image->getWidth();
                $image_height = $image->getHeight();
                $minimum_width = config('craydle.images.minimum_width');
                $minimum_height = config('craydle.images.minimum_height');

                if($image_width < $minimum_width){
                    @unlink($file_upload_path);

                    $this->hasError = true;
                    $this->errorMessage = sprintf(
                        LanguageTranslationHelper::translate('courses.errors.image_should_below_minimum_width'),
                        $minimum_width
                    );
                }else{
                    if($image_height < $minimum_height){
                        @unlink($file_upload_path);

                        $this->hasError = true;
                        $this->errorMessage = sprintf(
                            sprintf(
                                LanguageTranslationHelper::translate('courses.errors.image_should_below_minimum_height'),
                                $minimum_height
                            )
                        );
                    }
                }

                $minimum_aspect_ration_multiplier = config('craydle.images.allowed_aspect_ration_minimum_multiplier');
                $maximum_aspect_ration_multiplier = config('craydle.images.allowed_aspect_ration_maximum_multiplier');
                $aspect_ration_multiplier = CraydelHelperFunctions::imageAspectRationMultiplier($image_width,$image_height);

                if(!(($aspect_ration_multiplier >= $minimum_aspect_ration_multiplier) && ($aspect_ration_multiplier <= $maximum_aspect_ration_multiplier))){
                    @unlink($file_upload_path);

                    $this->hasError = true;
                    $this->errorMessage = sprintf(
                        LanguageTranslationHelper::translate('courses.errors.image_have_an_invalid_aspect_ration'),
                        $image_field_name
                    );
                }
            }

            $this->staged_images[''.$image_field_name.''] = [
                'status' => $this->hasError,
                'message' => $this->errorMessage,
                'uploaded_image_path' => $file_upload_path
            ];
        }else{
            $this->hasError = false;
            $this->errorMessage = null;
        }

        return $this;
    }

    /**
     * Validate course meta keywords
     *
     * @param string|null $meta_keywords
     * @return ValidateCourseDetails
     */
    private function validateMetaKeyWords(?string $meta_keywords): ValidateCourseDetails
    {
        if(!CraydelHelperFunctions::isNull($meta_keywords) && !(new Validator())::optional((new Validator())::stringVal())->validate($meta_keywords)){
            $this->hasError = true;
            $this->errorMessage = LanguageTranslationHelper::translate('courses.errors.invalid_meta_keywords');
        }else{
            $this->hasError = false;
        }
        return $this;
    }

    /**
     * Validate course meta description
     *
     * @param string|null $meta_description
     *
     * @return ValidateCourseDetails
     */
    private function validateMetaDescription(?string $meta_description): ValidateCourseDetails
    {
        if(!CraydelHelperFunctions::isNull($meta_description) && !(new Validator())::optional((new Validator())::stringVal())->validate($meta_description)){
            $this->hasError = true;
            $this->errorMessage = LanguageTranslationHelper::translate('courses.errors.invalid_meta_description');
        }else{
            $this->hasError = false;
        }
        return $this;
    }

    /**
     * Validate course linked blog articles
     *
     * @param string|null $linked_blog_articles
     *
     * @return ValidateCourseDetails
     */
    private function validateLinkedBlogArticles(?string $linked_blog_articles): ValidateCourseDetails
    {
        if(!CraydelHelperFunctions::isNull($linked_blog_articles) && !(new Validator())::optional((new Validator())::json())->validate($linked_blog_articles)){
            $this->hasError = true;
            $this->errorMessage = LanguageTranslationHelper::translate('courses.errors.invalid_linked_blog_articles');
        }else{
            $this->hasError = false;
        }
        return $this;
    }
}
