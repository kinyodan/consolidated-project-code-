<?php
namespace App\Http\Controllers\Courses\Commands;

use App\Events\CourseCreatedEvent;
use App\Http\Controllers\Courses\CourseController;
use App\Http\Controllers\CraydelTypes\CraydelJSONResponseType;
use App\Http\Controllers\Helpers\CraydelHelperFunctions;
use App\Http\Controllers\Helpers\GetLoggedIUserHelper;
use App\Http\Controllers\Helpers\LanguageTranslationHelper;
use App\Jobs\UploadImageToCDNJob;
use App\Models\BulkDelete;
use App\Models\Course;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PublishCourseCommandController

{
    /**
     * @var CourseController
     */
    protected $courseController;

    /**
     * Constructor
     * @param CourseController $courseController
     */
    public function __construct(CourseController $courseController){
        $this->courseController = $courseController;
    }

    /**
     * Feature course
     *
     * @param Request $request
     * @param string|null $course_code
     *
     * @return JsonResponse
     */
    public function publish(Request $request, ?string $course_code): JsonResponse
    {
        try{
            $user = GetLoggedIUserHelper::getUser($request);
            $username = $user->username ?? 'Course Admin';
            $course_code = CraydelHelperFunctions::toCleanString($course_code);
            if(empty($course_code)){
                throw new Exception("Missing course code");
            }
            if(!DB::table((new Course())->getTable())->where('course_code', $course_code)->exists()){
                throw new Exception("Invalid course code");
            }

            $current_status = null;
            DB::transaction(function () use($course_code,$username, &$current_status){
                $current_status = DB::table((new Course())->getTable())->where('course_code', trim($course_code))->value('is_published');
                if($current_status == 1){
                    DB::table((new Course())->getTable())->where('course_code', $course_code)->update([
                        'is_active' => 1,
                        'has_updates' => 1,
                        'should_unpublish'=>0,
                        'is_picked_for_unpublishing'=>0,
                        'updated_by' => $username,
                        'updated_at' => Carbon::now()->toDateTimeString(),
                    ]);


                    return $this->courseController->respondInJSON(new CraydelJSONResponseType(
                        true,
                        LanguageTranslationHelper::translate('courses.success.is_published')
                    ));
                }else{
                    DB::table((new Course())->getTable())->where('course_code', $course_code)->update([
                        'is_published' => 1,
                        'is_active' => 1,
                        'has_updates' => 1,
                        'should_unpublish'=>0,
                        'is_picked_for_unpublishing'=>0,
                        'updated_by' => $username,
                        'updated_at' => Carbon::now()->toDateTimeString(),
                        ]);
                    return $this->courseController->respondInJSON(new CraydelJSONResponseType(
                        true,
                        LanguageTranslationHelper::translate('courses.success.published')
                    ));

                }
            });
            dispatch((new UploadImageToCDNJob($course_code)))->onQueue('upload_images_to_cdn');
            event(new CourseCreatedEvent($course_code));
            if($current_status == 1){return $this->courseController->respondInJSON(new CraydelJSONResponseType(
                    true,
                    LanguageTranslationHelper::translate('courses.success.is_published')
                ));
            }else{return $this->courseController->respondInJSON(new CraydelJSONResponseType(
                    true,
                    LanguageTranslationHelper::translate('courses.success.published')
                ));
            }
        }catch (Exception $exception){
            $this->courseController->logException($exception);
            return $this->courseController->respondInJSON(new CraydelJSONResponseType(false, $exception->getMessage()));
        }
    }

    /**
     * @param $course_code
     * @param $batch_no
     * @return void
     */
    public static function publishSelectedCourse($course_code, $batch_no)
    {
        $request = new Request();
        $course= new CourseController();
        $deleletecourse=$course->publish($course_code,$request);
        $deleletedcourse=json_decode($deleletecourse->getContent());
        if($deleletedcourse->status==true){
            DB::table((new BulkDelete())->getTable())
                ->where('course_code', $course_code)
                ->where('batch_no', $batch_no)
                ->update([
                    'is_processed' => 1,
                    'updated_at'=>Carbon::now()->toDateTimeString(),
                ]);
        }else{
            DB::table((new BulkDelete())->getTable())
                ->where('course_code', $course_code)
                ->where('batch_no', $batch_no)
                ->update([
                    'processing_error' => $deleletedcourse->message,
                    'updated_at'=>Carbon::now()->toDateTimeString(),
                ]);
        }

    }

}
