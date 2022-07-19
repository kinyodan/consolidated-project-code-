<?php
namespace App\Http\Controllers\Courses\Commands;

use App\Http\Controllers\Courses\CourseController;
use App\Http\Controllers\CraydelTypes\CraydelJSONResponseType;
use App\Http\Controllers\Helpers\CraydelHelperFunctions;
use App\Http\Controllers\Helpers\GetLoggedIUserHelper;
use App\Http\Controllers\Helpers\LanguageTranslationHelper;
use App\Http\Controllers\Traits\CanLog;
use App\Jobs\DeleteImageCDNJob;
use App\Jobs\UnpublishCourseFromSearchEngineJob;
use App\Models\BulkDelete;
use App\Models\Course;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DeleteCourseCommandController
{
    use CanLog;

    /**
     * @var CourseController
     */
    protected $courseController;

    /**
     * Constructor
     * @param CourseController $courseController
     */
    public function __construct(CourseController $courseController)
    {
        $this->courseController = $courseController;
    }

    public static function deleteSelectedCourse(Request $request, $course_code, $batch_no)
    {
        $deleletecourse = $course->delete($course_code, $request);
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
        };
    }

    /**
     * Feature course
     *
     * @param Request $request
     * @param string|null $course_code
     *
     * @return JsonResponse
     */
    public function delete(Request $request, ?string $course_code): JsonResponse
    {
        try {
            $user = GetLoggedIUserHelper::getUser($request);
            $username = $user->username ?? 'Course Admin';
            $course_code = CraydelHelperFunctions::toCleanString($course_code);
            if (empty($course_code)) {
                throw new Exception("Missing course code");
            }
            if (!DB::table((new Course())->getTable())->where('course_code', $course_code)->exists()) {
                throw new Exception("Invalid course code");
            }
            $current_status = null;
            DB::transaction(function () use ($course_code, $username, &$current_status) {
                DB::table((new Course())->getTable())
                    ->where('course_code', $course_code)
                    ->update([
                        'is_deleted' => 1,
                        'is_active' => 0,
                        'should_unpublish' => 1,
                        'deleted_by' => $username,
                        'deleted_at' => Carbon::now()->toDateTimeString(),
                    ]);
            });
            dispatch((new UnpublishCourseFromSearchEngineJob($course_code)))->onQueue('remove_course_to_search_engine');
            dispatch((new DeleteImageCDNJob($course_code)))->onQueue('delete_images_to_cdn');
            $current_status = DB::table((new Course())->getTable())->where('course_code', trim($course_code))->value('is_deleted');
            if ($current_status == 1) {
                return $this->courseController->respondInJSON(new CraydelJSONResponseType(
                    true,
                    LanguageTranslationHelper::translate('courses.success.deleted')
                ));
            }
        } catch (Exception $exception) {
            $this->courseController->logException($exception);
            return $this->courseController->respondInJSON(new CraydelJSONResponseType(false, $exception->getMessage()));
        }
    }
}
