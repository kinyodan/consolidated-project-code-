<?php
namespace App\Http\Controllers\Courses\Commands;
use App\Http\Controllers\Courses\CourseController;
use App\Http\Controllers\CraydelTypes\CraydelJSONResponseType;
use App\Http\Controllers\Helpers\CraydelHelperFunctions;
use App\Http\Controllers\Helpers\GetLoggedIUserHelper;
use App\Http\Controllers\Helpers\LanguageTranslationHelper;
use App\Http\Controllers\Traits\CanLog;
use App\Jobs\ProcessBulkPublishCourse;
use App\Models\Course;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Models\BulkDelete;
class BulkPublishCourseCommandController
{

     use CanLog;
    /**
     * @var CourseController
     */
    protected  $courseController;

    /**
     * Constructor
     * @param CourseController $courseController
     */
    public function __construct(CourseController $courseController)
    {
        $this->courseController = $courseController;
    }

    /**
     * Feature course
     *
     * @param Request $request
     * @param string|null $course_code
     *
     * @return string
     */
    public function bulkPublish(Request $request): JsonResponse
    {
        try {
            $data = [];
            $batch_number = Str::random(10);
            $chunk_limit = config('craydle.course_upload_chunk_size');
            if(CraydelHelperFunctions::isJson($request->input('course_codes')) !== true){
                throw new \Exception('The course_codes has to be Json.');
            }
            $course_code = json_decode($request->course_codes);
            foreach ($course_code as $row) {
                DB::table((new Course())->getTable())
                    ->where('course_code', $row)
                    ->update([
                        'is_active' => 1,
                        'is_published' => 1,
                        'updated_at' => Carbon::now()->toDateTimeString()
                    ]);
                $data[] = [
                    'course_code' => $row,
                    'batch_no' => $batch_number,
                    'action_type' => 'publish',
                    'created_at' => Carbon::now()->toDateTimeString()
                ];
            }
            $data = array_chunk($data, $chunk_limit);
            foreach ($data as $chunk) {
                DB::table('bulk_action')->insertOrIgnore($chunk);
            }
            return $this->courseController->respondInJSON(new CraydelJSONResponseType(
                true,
                LanguageTranslationHelper::translate('courses.success.bulk_published')
            ));
        } catch (Exception $exception) {
            $this->courseController->logException($exception);
            return $this->courseController->respondInJSON(new CraydelJSONResponseType(false, $exception->getMessage()));
        }
    }

    /**
     * @param $course
     * @return void
     */
    public static function selectedCourse($course)
    {
        try {
            if (isset($course->course_code) && !empty($course->course_code)) {
                $updated = DB::table((new BulkDelete())->getTable())
                    ->where('course_code', $course->course_code)
                    ->update([
                        'picked_for_processing' => 1,
                        'updated_at' => Carbon::now()->toDateTimeString()
                    ]);
                if ($updated) {
                    $course_code = $course->course_code;
                    $batch_no = $course->batch_no;
                    dispatch((new ProcessBulkPublishCourse($course_code,$batch_no)))->onQueue('delete_course_index');
                } else {
                    throw new \Exception("Unable to mark the course as staged for publish.");
                }
            }
        } catch (Exception $exception) {
            self::craydelExceptionLogger($exception);

        }
    }


}
