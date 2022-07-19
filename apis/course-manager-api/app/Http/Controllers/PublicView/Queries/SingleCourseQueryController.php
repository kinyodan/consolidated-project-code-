<?php
namespace App\Http\Controllers\PublicView\Queries;

use App\Http\Controllers\Courses\CourseController;
use App\Http\Controllers\CraydelTypes\CraydelJSONResponseType;
use App\Http\Controllers\Helpers\CraydelHelperFunctions;
use App\Http\Controllers\Helpers\LanguageTranslationHelper;
use App\Http\Controllers\PublicView\CoursesPublicViewController;
use App\Models\Course;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class SingleCourseQueryController
{
    /**
     * @var CoursesPublicViewController $coursesPublicViewController
    */
    protected $coursesPublicViewController;

    /**
     * Construct
    */
    public function __construct(CoursesPublicViewController $coursesPublicViewController)
    {
        $this->coursesPublicViewController = $coursesPublicViewController;
    }

    /**
     * Search for courses
     *
     * @param string|null $course_code
     * @return JsonResponse
     */
    public function course(?string $course_code): JsonResponse
    {
        try{
            if(empty($course_code)){
                throw new \Exception(
                    LanguageTranslationHelper::translate('courses.errors.invalid_course_code')
                );
            }

            $course_code = CraydelHelperFunctions::toCleanString($course_code);

            if(!DB::table((new Course())->getTable())->where('course_code', $course_code)->exists()){
                throw new \Exception(
                    LanguageTranslationHelper::translate('courses.errors.invalid_course_code')
                );
            }

            $course = Course::with(['type', 'discipline', 'learningMode', 'linkedDisciplines'])
                ->where('course_code', $course_code)
                ->first();

            return $this->coursesPublicViewController->respondInJSON(new CraydelJSONResponseType(
                true,
                LanguageTranslationHelper::translate('courses.success.details_shown'), [
                    'course_details' => $course,
                    'similar_courses' => CourseController::getRelatedCourse($course_code)
                ]
            ));
        }catch (\Exception $exception){
            $this->coursesPublicViewController->logException($exception);

            return $this->coursesPublicViewController->respondInJSON(new CraydelJSONResponseType(
                false,
                $exception->getMessage()
            ));
        }
    }
}
