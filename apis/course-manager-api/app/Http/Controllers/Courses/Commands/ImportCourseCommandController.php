<?php
namespace App\Http\Controllers\Courses\Commands;

use App\Events\CourseCreatedEvent;
use App\Http\Controllers\Courses\CourseController;
use App\Http\Controllers\CraydelTypes\CraydelJSONResponseType;
use App\Http\Controllers\Helpers\AcademicDisciplineHelper;
use App\Http\Controllers\Helpers\CourseTypesHelper;
use App\Http\Controllers\Helpers\CraydelHelperFunctions;
use App\Http\Controllers\Helpers\CraydelInternalResponseHelper;
use App\Http\Controllers\Helpers\CreateBulkCourseUploadTemplateExcelFileHelper;
use App\Http\Controllers\Helpers\GetLoggedIUserHelper;
use App\Http\Controllers\Helpers\LanguageTranslationHelper;
use App\Http\Controllers\Helpers\LearningModeHelper;
use App\Http\Controllers\Helpers\MySQLFunctionsHelper;
use App\Http\Controllers\Traits\CanCache;
use App\Http\Controllers\Traits\CanLog;
use App\Models\Course;
use App\Models\CourseBulkImport;
use AWS\CRT\Log;
use Box\Spout\Common\Exception\IOException;
use Box\Spout\Common\Exception\UnsupportedTypeException;
use Box\Spout\Reader\Exception\ReaderNotOpenedException;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Rap2hpoutre\FastExcel\FastExcel;

class ImportCourseCommandController
{
    use CanLog, CanCache;

    /**
     * @var $allowedImageMimeTypes
     */
    public $allowedImageMimeTypes = [
        'application/xml',
        'application/vnd.ms-excel',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
    ];

    /**
     * @var CourseController
     */
    protected $courseController;

    /**
     * @var $course_template_headers
    */
    public static $course_template_headers = [
        'Course name',
        'Academic Discipline',
        'Course Description',
        'Course Type',
        'Graduate Level',
        'Learning Mode',
        'Attendance Type',
        'Duration Category',
        'Duration',
        'Standard Fee Billed Per',
        'Standard Fee Payable',
        'Standard Fee Currency',
        'Enrollment Dates',
        'Requirements',
        'Maximum Scholarship available',
        'Accredited By',
        'Accreditation Organization Website',
        'Accreditation Acronym',
        'Course Image',
        'University URL',
        'Faculty Code',
        'Institution Course Code',
        'Course Overview',
        'Website Link',
        'Application form Link',
        'Course Structure',
        'Foreign Student Fee',
        'Foreign Fees Structure',
        'Standard Fees Structure',
        'Fees Structure URL'
    ];

    /**
     * Constructor
     * @param CourseController $courseController
     */
    public function __construct(CourseController $courseController){
        $this->courseController = $courseController;
    }

    /**
     * Validate the course upload file.
     * @param Request $request
     *
     * @return CraydelInternalResponseHelper
     */
    private function validate(Request $request): CraydelInternalResponseHelper
    {
        $institution_code = $request->input('institution_code');
        $country_code = $request->input('country_code');

        if(empty($institution_code)){
            return (new CraydelInternalResponseHelper(
                false,
                LanguageTranslationHelper::translate('courses.errors.missing_institution_code')
            ));
        }

        if(empty($country_code)){
            return (new CraydelInternalResponseHelper(
                false,
                LanguageTranslationHelper::translate('courses.errors.missing_country_code')
            ));
        }

        if(!$request->hasFile('courses_list')){
            return (new CraydelInternalResponseHelper(
                false,
                LanguageTranslationHelper::translate('courses.errors.missing_course_file')
            ));
        }

        $courses_file = $request->file('courses_list');
        $file_mime_type = $courses_file->getClientMimeType();
        $file_mime_type = CraydelHelperFunctions::toCleanString(strtolower($file_mime_type));

        if (!in_array($file_mime_type, $this->allowedImageMimeTypes)) {
            return (new CraydelInternalResponseHelper(
                false,
                sprintf(
                    LanguageTranslationHelper::translate('courses.errors.invalid_course_file'),
                    implode('', $this->allowedImageMimeTypes)
                )
            ));
        }

        $courses_file_size = $courses_file->getSize();
        $file_size_in_mbs = CraydelHelperFunctions::convertBytesToMBs($courses_file_size);

        if(isset($file_size_in_mbs)){
            $maximum_allowed = config('craydle.security.maximum_uploaded_file_size', 10);

            if(floatval($file_size_in_mbs) > $maximum_allowed){
                return (new CraydelInternalResponseHelper(
                    false,
                    sprintf(
                        LanguageTranslationHelper::translate('courses.errors.invalid_course_file_too_big'),
                        $maximum_allowed
                    )
                ));
            }
        }else{
            return (new CraydelInternalResponseHelper(
                false,
                LanguageTranslationHelper::translate('courses.errors.invalid_course_image_file_size_can_not_be_found')
            ));
        }

        $staged_files_path = storage_path().DIRECTORY_SEPARATOR.'staged-files'.DIRECTORY_SEPARATOR.'courses-import'.DIRECTORY_SEPARATOR;
        $course_file_name = CraydelHelperFunctions::makeRandomString(20,null, true).'.'.$courses_file->getClientOriginalExtension();
        $courses_file->move($staged_files_path, $course_file_name);
        $file_upload_path = $staged_files_path.$course_file_name;

        $validate_headers = CreateBulkCourseUploadTemplateExcelFileHelper::validateFileHeaders(
            $file_upload_path,
            self::$course_template_headers
        );

        if(!$validate_headers->status){
            @unlink($file_upload_path);

            return (new CraydelInternalResponseHelper(
                false,
                $validate_headers->message ?? "The file is invalid"
            ));
        }

        if(file_exists($file_upload_path)){
            return (new CraydelInternalResponseHelper(
                true,
                'Uploaded',[
                    'courses_file' => $file_upload_path,
                    'institution_code' => $institution_code
                ]
            ));
        }else{
            return (new CraydelInternalResponseHelper(
                false,
                LanguageTranslationHelper::translate('courses.errors.error_uploading_course_file')
            ));
        }
    }

    /**
     * Import
     * @param Request $request
     *
     * @return JsonResponse
     * @throws Exception
     */
    public function import(Request $request): JsonResponse
    {
        $validate = $this->validate($request);
        $user = GetLoggedIUserHelper::getUser($request);
        $institution_code = $request->input('institution_code');
        $country_code = $request->input('country_code');


        if(!$validate->status){
            return $this->courseController->respondInJSON((new CraydelJSONResponseType(
                $validate->status,
                $validate->message
            )));
        }

        if(!isset($validate->data['courses_file']) || empty($validate->data['courses_file'])){
            return $this->courseController->respondInJSON((new CraydelJSONResponseType(
                false,
                LanguageTranslationHelper::translate('courses.errors.error_uploading_course_file')
            )));
        }

        try {
            $courses = (new FastExcel())->import($validate->data['courses_file']);

            if(!$courses instanceof Collection) {
                return $this->courseController->respondInJSON((new CraydelJSONResponseType(
                    false,
                    LanguageTranslationHelper::translate('courses.errors.error_while_reading_courses_file')
                )));
            }

            @unlink($validate->data['courses_file']);

            $courses = $courses->reject(function ($course){
                return !isset($course['Course name']) || empty($course['Course name']);
            })->values()->toArray();

            $courses = CraydelHelperFunctions::array_unique_multidimensional($courses);
            $final_courses_count = count($courses);

            if($final_courses_count <= 0){
                throw new Exception("No data to import after validation.");
            }

            $courses = collect($courses)->unique(function ($course){
                if(isset($course['Course name']) && isset($course['Graduate Level'])){
                    return $course['Course name'].$course['Graduate Level'];
                }else{
                    throw new Exception('Missing course name and graduate level columns.');
                }
            })->values()->toArray();

            $final_courses_count = count($courses);

            if($final_courses_count <= 0){
                throw new Exception("No data to import after removing duplicate courses.");
            }

            $courses = array_chunk($courses, config('craydle.course_upload_chunk_size', 10));
            $saved = false;

            DB::transaction(function () use($courses, $institution_code, $country_code, $user, &$saved){
                foreach ($courses as $course){
                    DB::table((new CourseBulkImport())->getTable())->insert([
                        'file_data' => json_encode([
                            'institution_code' => $institution_code,
                            'country_code' => $country_code,
                            'data' => json_encode($course),

                            'created_by' => isset($user->user_code) && !empty($user->user_code) ? trim($user->user_code) : null
                        ]),
                        'is_processed' => 0,
                        'total_records' => 0,
                        'successful_records' => 0,
                        'failed_records' => 0,
                        'failure_reasons' => null,
                        'user_email' => isset($user->email) && !empty($user->email) ? trim($user->email) : null,
                        'created_at' => Carbon::now()->toDateTimeString()
                    ]);
                }

                $saved = true;
            });

            if($saved){
                return $this->courseController->respondInJSON((new CraydelJSONResponseType(
                    true,
                    sprintf(
                        LanguageTranslationHelper::translate('courses.success.imported_awaiting_processing'),
                        $final_courses_count
                    )
                )));
            }else{
                return $this->courseController->respondInJSON((new CraydelJSONResponseType(
                    false,
                    LanguageTranslationHelper::translate('courses.errors.error_while_reading_courses_file')
                )));
            }
        } catch (IOException | UnsupportedTypeException | ReaderNotOpenedException | Exception $e) {
            $this->logException($e);

            return $this->courseController->respondInJSON((new CraydelJSONResponseType(
                false,
                LanguageTranslationHelper::translate('courses.errors.error_while_reading_courses_file')
            )));
        }
    }

    /**
     * Process staged courses data
     *
     * @param int|null $course_batch_id
     * @param object|null $course_batch
     *
     * @return void
    */
    public function process(?int $course_batch_id, ?object $course_batch): void
    {
        try{
            if(is_null($course_batch_id) || empty($course_batch_id)){
                throw new Exception('Missing courses batch ID.');
            }

            if(is_null($course_batch) || !is_object($course_batch)){
                throw new Exception('Invalid course batch.');
            }

            $institution_code = isset($course_batch->institution_code) ? trim($course_batch->institution_code) : null;
            $country_code = isset($course_batch->country_code) ? trim($course_batch->country_code) : null;
            $courses = isset($course_batch->data) ? json_decode($course_batch->data) : array();
            if(is_null($institution_code) || empty($institution_code)){
                throw new Exception('Invalid institution code');
            }

            if(is_null($country_code) || empty($country_code)){
                throw new Exception('Invalid country code');
            }

            if(is_null($courses) || count($courses) <= 0){
                throw new Exception('Invalid courses list.');
            }

            $courses_list = collect($courses)->map(function ($item) use($country_code, $institution_code,$course_batch_id){
                $course_name = isset($item->{'Course name'}) && !empty($item->{'Course name'}) ? trim($item->{'Course name'}) : "";
                $academic_discipline = isset($item->{'Academic Discipline'}) && !empty($item->{'Academic Discipline'}) ? AcademicDisciplineHelper::getDisciplineIDByName(trim($item->{'Academic Discipline'})) : 1;
                $course_type = isset($item->{'Course Type'}) && !empty($item->{'Course Type'}) ? CourseTypesHelper::getCourseTypeIDByName(trim($item->{'Course Type'})) : 1;
                $learning_mode = isset($item->{'Learning Mode'}) && !empty($item->{'Learning Mode'}) ? LearningModeHelper::getLearningModeIDByName(trim($item->{'Learning Mode'})) : 1;
                if(!empty($course_name)){
                    return [
                        'country_code' => $country_code,
                        'institution_code' => $institution_code,
                        'course_name_slug' => CraydelHelperFunctions::slugifyString($course_name),
                        'course_name' => CraydelHelperFunctions::toCleanString($course_name),
                        'course_code' => CraydelHelperFunctions::makeRandomString(10, 'CC', false),
                        'is_featured' => 0,
                        'is_deleted' => 0,
                        'is_active' => 1,
                        'description' => isset($item->{'Course Description'}) ? nl2br($item->{'Course Description'}) : null,
                        'course_overview' => isset($item->{'Course Overview'}) && !empty($item->{'Course Overview'}) ? nl2br(trim($item->{'Course Overview'})) : null,
                        'discipline_code' => $academic_discipline,
                        'course_type' => $course_type,
                        'graduate_level' => isset($item->{'Graduate Level'}) && !empty($item->{'Graduate Level'}) ? trim($item->{'Graduate Level'}) : "Degree",
                        'internal_course_ranking' => 0,
                        'institution_course_code' => isset($item->{'Institution Course Code'}) && !empty($item->{'Institution Course Code'}) ? trim($item->{'Institution Course Code'}) : null,
                        'attendance_type' => isset($item->{'Attendance Type'}) && !empty($item->{'Attendance Type'}) ? trim($item->{'Attendance Type'}) : "Full Time",
                        'learning_mode' => $learning_mode,
                        'faculty_code' => isset($item->{'Faculty Code'}) && !empty($item->{'Faculty Code'}) ? trim($item->{'Faculty Code'}) : null,
                        'institution_website_course_url' => isset($item->{'Website Link'}) && filter_var($item->{'Website Link'}, FILTER_VALIDATE_URL) ? trim($item->{'Website Link'}) : null,
                        'institution_website_application_form_url' => isset($item->{'Application form Link'}) && filter_var($item->{'Application form Link'}, FILTER_VALIDATE_URL) ? trim($item->{'Application form Link'}) : null,
                        'course_requirements' => isset($item->{'Requirements'}) && !empty($item->{'Requirements'}) ? nl2br($item->{'Requirements'}) : null,
                        'content_completeness_score' => 0,
                        'enrollment_details' => isset($item->{'Enrollment Dates'}) && !empty($item->{'Enrollment Dates'}) ? trim($item->{'Enrollment Dates'}) : null,
                        'enrollment_in_progress' => 0,
                        'standard_fee_billing_type' => isset($item->{'Standard Fee Billed Per'}) && !empty($item->{'Standard Fee Billed Per'}) ? trim($item->{'Standard Fee Billed Per'}) : 'Total Cost',
                        'standard_fee_payable' => isset($item->{'Standard Fee Payable'}) && !empty($item->{'Standard Fee Payable'}) ? floatval($item->{'Standard Fee Payable'}) : 0,
                        'standard_fee_payable_usd' => isset($item->{'Standard Fee Currency'}) && !empty($item->{'Standard Fee Currency'}) ? trim($item->{'Standard Fee Currency'}) : "USD",
                        'standard_fee_breakdown' => isset($item->{'Standard Fees Structure'}) && !empty($item->{'Standard Fees Structure'}) ? trim($item->{'Standard Fees Structure'}) : null,
                        'foreign_student_fee_billing_type' => isset($item->{'Standard Fee Billed Per'}) && !empty($item->{'Standard Fee Billed Per'}) ? trim($item->{'Standard Fee Billed Per'}) : 'Total Cost',
                        'foreign_student_fee_payable' => isset($item->{'Foreign Student Fee'}) && !empty($item->{'Foreign Student Fee'}) ? floatval($item->{'Foreign Student Fee'}) : 0,
                        'foreign_student_fee_breakdown' => isset($item->{'Foreign Fees Structure'}) && !empty($item->{'Foreign Fees Structure'}) ? trim($item->{'Foreign Fees Structure'}) : null,
                        'temp_course_image_url' => isset($item->{'Course Image'}) && !empty($item->{'Course Image'}) ? trim($item->{'Course Image'}) : null,
                        'course_structure_breakdown' => isset($item->{'Course Structure'}) && !empty($item->{'Course Structure'}) ? nl2br($item->{'Course Structure'}) : null,
                        'course_duration' => isset($item->{'Duration'}) && !empty($item->{'Duration'}) ? floatval($item->{'Duration'}) : 0,
                        'course_duration_category' => isset($item->{'Duration Category'}) && !empty($item->{'Duration Category'}) ? trim($item->{'Duration Category'}) : null,
                        'maximum_scholarship_available' => isset($item->{'Maximum Scholarship available'}) && !empty($item->{'Maximum Scholarship available'}) ? trim($item->{'Maximum Scholarship available'}) : null,
                        'accredited_by' => isset($item->{'Accredited By'}) && !empty($item->{'Accredited By'}) ? trim($item->{'Accredited By'}) : null,
                        'accredited_by_acronym' => isset($item->{'Accreditation Acronym'}) && !empty($item->{'Accreditation Acronym'}) ? trim($item->{'Accreditation Acronym'}) : null,
                        'accreditation_organization_url' => isset($item->{'Accreditation Organization Website'}) && filter_var($item->{'Accreditation Organization Website'}, FILTER_VALIDATE_URL) ? trim($item->{'Accreditation Organization Website'})  : null,
                        'created_at' => Carbon::now()->toDateTimeString(),
                        'currency' => isset($item->{'Standard Fee Currency'}) && !empty($item->{'Standard Fee Currency'}) ? trim($item->{'Standard Fee Currency'}) : null,
                        'fees_structure_url' => isset($item->{'Fees Structure URL'}) && filter_var($item->{'Accreditation Organization Website'}, FILTER_VALIDATE_URL) ? trim($item->{'Fees Structure URL'}) : null,
                        'popularity' => null,
                        'indexing_object_id' => null,
                        'has_updates' => 0,
                        'batch_no' =>$course_batch_id

                    ];
                }else{
                    return null;
                }
            })->reject(function ($item){
                return is_null($item);
            });

            $course_codes = $courses_list->map(function ($courses_list){
                return [
                    'course_code' => isset($courses_list['course_code']) && !empty($courses_list['course_code']) ? $courses_list['course_code'] : null
                ];
            })->reject(function ($course){
                return !isset($course['course_code']) || is_null($course['course_code']);
            })->unique('course_code')->values()->toArray();

            DB::transaction(function () use($courses_list){
                DB::table((new Course())->getTable())
                    ->insertOrIgnore($courses_list->values()->toArray());
            });

            if(count($course_codes) > 0){
                foreach ($course_codes as $key => $course){
                    if(isset($course['course_code']) && !empty($course['course_code'])){
                        event(new CourseCreatedEvent($course['course_code']));
                    }
                }
            }

            DB::table((new CourseBulkImport())->getTable())
                ->where('id', $course_batch_id)
                ->update([
                    'is_processed' => 1,
                    'total_records' => count($courses_list),
                    'updated_at' => Carbon::now()->toDateTimeString(),
                    'successful_records' => count($course_codes),
                    'failed_records' => count($courses_list) - count($course_codes),

                ]);
        }catch (Exception $exception){
            $this->logException($exception);

            if(!is_null($course_batch_id)){
                DB::table((new CourseBulkImport())->getTable())
                    ->where('id', $course_batch_id)
                    ->update([
                        'failure_reasons' => $exception->getMessage(),
                        'updated_at' => Carbon::now()->toDateTimeString()
                    ]);
            }
        }
    }
}
