<?php
namespace App\Http\Controllers\PublicView\Queries;

use App\Http\Controllers\CraydelTypes\CraydelJSONResponseType;
use App\Http\Controllers\Helpers\AcademicDisciplineHelper;
use App\Http\Controllers\PublicView\CoursesPublicViewController;
use App\Http\Controllers\Traits\CanCache;
use App\Http\Controllers\Traits\CanLog;
use App\Http\Controllers\Traits\CanRespond;
use App\Models\AcademicDiscipline;
use App\Models\Course;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GetCoursesPerDisciplineQueryController
{
    use CanRespond, CanLog, CanCache;

    /**
     * @var CoursesPublicViewController $coursesPublicViewController
     */
    protected CoursesPublicViewController $coursesPublicViewController;

    /**
     * Construct
     */
    public function __construct(CoursesPublicViewController $coursesPublicViewController)
    {
        $this->coursesPublicViewController = $coursesPublicViewController;
    }

    /**
     * Get courses per discipline
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function courses(Request $request): JsonResponse
    {
        try {
            $courses_based_on_discipline = self::cache('courses_based_on_discipline');

            if(is_null($courses_based_on_discipline)){
                $courses_based_on_discipline = self::cache('courses_based_on_discipline', call_user_func(function () use ($request) {
                    return $this->_disciplineCourses((int)$request->input('discipline_code'));
                }));
            }

            return $this->coursesPublicViewController->respondInJSON(new CraydelJSONResponseType(
                true,
                'Listed',
                $courses_based_on_discipline
            ));
        } catch (Exception $exception) {
            $this->coursesPublicViewController->logException($exception);

            return $this->coursesPublicViewController->respondInJSON(new CraydelJSONResponseType(
                false,
                $exception
            ));
        }
    }

    /**
     * Fetch courses per discipline
     */
    protected function _disciplineCourses(?string $discipline_code): ?array
    {
        $courses = DB::table((new Course())->getTable())
            ->where('discipline_code', AcademicDisciplineHelper::getDisciplineIDByCode($discipline_code))
            ->where('is_active', 1)
            ->get();

        $courses_list =  collect($courses)
            ->map(function ($course) {
                return [
                    'course_code' => $course->course_code,
                    'course_small_image' => $course->course_small_image,
                    'course_image' => $course->course_image,
                    'discipline' => DB::table((new AcademicDiscipline())->getTable())->where('discipline_code', $course->discipline_code)->value('discipline_name'),
                    'url_course_slug' => $course->course_code . '-' . $course->course_name_slug,
                    'course_name' => $course->course_name,
                ];
            })
            ->sortByDesc('course_name')
            ->toArray();

        $max = count($courses_list)-10;
        $lower_limit = rand(0, $max);
        $upper_limit=$lower_limit+4;

        return array_slice($courses_list, $lower_limit, $upper_limit);
    }
}
