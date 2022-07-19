<?php
namespace App\Http\Controllers\Courses\Commands;

use App\Events\CourseUpdatedEvent;
use App\Http\Controllers\Courses\CourseController;
use App\Http\Controllers\CraydelTypes\CraydelJSONResponseType;
use App\Http\Controllers\Helpers\CraydelHelperFunctions;
use App\Http\Controllers\Helpers\LanguageTranslationHelper;
use App\Models\Course;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FeatureCourseCommandController
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
    public function feature(Request $request, ?string $course_code): JsonResponse
    {
        try{
            $course_code = CraydelHelperFunctions::toCleanString($course_code);

            if(empty($course_code)){
                throw new Exception("Missing course code");
            }

            if(!DB::table((new Course())->getTable())->where('course_code', $course_code)->exists()){
                throw new Exception("Invalid course code");
            }

            $current_status = null;

            DB::transaction(function () use($course_code, &$current_status){
                $current_status = DB::table((new Course())->getTable())
                    ->where('course_code', trim($course_code))
                    ->value('is_featured');

                if($current_status == 1){
                    DB::table((new Course())->getTable())
                        ->where('course_code', $course_code)
                        ->update([
                            'is_featured' => 0
                        ]);
                }else{
                    DB::table((new Course())->getTable())
                        ->where('course_code', $course_code)
                        ->update([
                            'is_featured' => 1
                        ]);
                }
            });

            event(new CourseUpdatedEvent($course_code));

            if($current_status == 0){
                return $this->courseController->respondInJSON(new CraydelJSONResponseType(
                    true,
                    LanguageTranslationHelper::translate('courses.success.featured')
                ));
            }else{
                return $this->courseController->respondInJSON(new CraydelJSONResponseType(
                    true,
                    LanguageTranslationHelper::translate('courses.success.un_featured')
                ));
            }
        }catch (Exception $exception){
            $this->courseController->logException($exception);

            return $this->courseController->respondInJSON(new CraydelJSONResponseType(false, $exception->getMessage()));
        }
    }
}
