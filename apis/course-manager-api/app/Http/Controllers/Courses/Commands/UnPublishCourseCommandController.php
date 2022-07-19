<?php
namespace App\Http\Controllers\Courses\Commands;

use App\Http\Controllers\Courses\CourseController;
use App\Http\Controllers\CraydelTypes\CraydelJSONResponseType;
use App\Http\Controllers\Helpers\CraydelHelperFunctions;
use App\Http\Controllers\Helpers\GetLoggedIUserHelper;
use App\Http\Controllers\Helpers\LanguageTranslationHelper;
use App\Jobs\DeleteImageCDNJob;
use App\Jobs\UnpublishCourseFromSearchEngineJob;
use App\Models\BulkDelete;
use App\Models\Course;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UnPublishCourseCommandController
{
    /**
     * @var CourseController
     */
    protected CourseController $courseController;

    /**
     * Constructor
     * @param CourseController $courseController
     */
    public function __construct(CourseController $courseController)
    {
        $this->courseController = $courseController;
    }

    /**
     * @param $course_code
     * @param $batch_no
     * @return void
     */
    public static function unpublishSelectedCourse($course_code, $batch_no)
    {
        $request = new Request();
        $course = new CourseController();
        $deleletecourse = $course->publish($course_code, $request);
        $deleletedcourse = json_decode($deleletecourse->getContent());
        if ($deleletedcourse->status == true) {
            DB::table((new BulkDelete())->getTable())
                ->where('course_code', $course_code)
                ->where('batch_no', $batch_no)
                ->update([
                    'is_processed' => 1,
                    'updated_at' => Carbon::now()->toDateTimeString(),
                ]);
        } else {
            DB::table((new BulkDelete())->getTable())
                ->where('course_code', $course_code)
                ->where('batch_no', $batch_no)
                ->update([
                    'processing_error' => $deleletedcourse->message,
                    'updated_at' => Carbon::now()->toDateTimeString(),
                ]);
        }
    }

    /**
     * Feature course
     *
     * @param Request $request
     * @param string|null $course_code
     *
     * @return JsonResponse
     */
    public function unpublish(Request $request, ?string $course_code): JsonResponse
    {
        try {
            $user = GetLoggedIUserHelper::getUser($request);
            $username = $user->username ?? 'Course Admin';
            $course_code = CraydelHelperFunctions::toCleanString($course_code);
            if (empty($course_code)) {
                throw new Exception('Missing course code');
            }
            if (!DB::table((new Course())->getTable())->where('course_code', $course_code)->exists()) {
                throw new Exception('Invalid course code');
            }
            $current_status = null;
            DB::transaction(function () use ($course_code, $username, &$current_status) {
                DB::table((new Course())->getTable())
                    ->where('course_code', $course_code)
                    ->update([
                        'is_published' => 0,
                        'is_active' => 0,
                        'is_deleted' => 0,
                        'updated_by' => $username,
                        'updated_at' => Carbon::now()->toDateTimeString()
                    ]);
            });
            dispatch((new UnpublishCourseFromSearchEngineJob($course_code, true)))->onQueue('delete_course_index');
            dispatch((new DeleteImageCDNJob($course_code)))->onQueue('delete_images_to_cdn');
            $current_status = DB::table((new Course())->getTable())->where('course_code', trim($course_code))->value('is_published');
            if ($current_status == 0) {
                return $this->courseController->respondInJSON(new CraydelJSONResponseType(
                    true,
                    LanguageTranslationHelper::translate('courses.success.unpublished')
                ));
            }
        } catch (Exception $exception) {
            $this->courseController->logException($exception);
            return $this->courseController->respondInJSON(new CraydelJSONResponseType(false, $exception->getMessage()));
        }
    }
}
