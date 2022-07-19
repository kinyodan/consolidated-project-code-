<?php
namespace App\Http\Controllers\PublicView\Queries;

use App\Http\Controllers\CraydelTypes\CraydelJSONResponseType;
use App\Http\Controllers\Helpers\CraydelHelperFunctions;
use App\Http\Controllers\Helpers\InstitutionsHelper;
use App\Http\Controllers\PublicView\CoursesPublicViewController;
use App\Http\Controllers\Traits\CanCache;
use App\Models\AcademicDiscipline;
use App\Models\Course;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GetTopCoursesQueryController
{
    use CanCache;

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
     * Get top courses
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function courses(Request $request): JsonResponse
    {
        try{
            $top_courses_based_on_lead_volume = self::cache('top_courses_based_on_lead_volume');

            if(is_null($top_courses_based_on_lead_volume)){
                $top_courses_based_on_lead_volume = self::cache('top_courses_based_on_lead_volume', call_user_func(function (){
                    $courses = $this->_topCourses();

                    return [
                        'undergraduate' => $courses[1] ?? null,
                        'postgraduate' => $courses[2] ?? null
                    ];
                }));
            }

            return $this->coursesPublicViewController->respondInJSON(new CraydelJSONResponseType(
                true,
                'Listed',
                $top_courses_based_on_lead_volume
            ));
        }catch (\Exception $exception){
            $this->coursesPublicViewController->logException($exception);

            return $this->coursesPublicViewController->respondInJSON( new CraydelJSONResponseType(
                false,
                $exception->getMessage()
            ));
        }
    }

    /**
     * Fetch top courses
    */
    protected function _topCourses(): ?array
    {
        $courses = DB::table((new Course())->getTable())
            ->select([
                'course_type',
                'total_leads_submitted_to_crm',
                'course_code',
                'course_small_image',
                'course_image',
                'institution_code',
                'discipline_code',
                'course_name_slug',
                'course_name'
            ])
            ->where('is_deleted', 0)
            ->where('is_active', 1)
            ->whereIn('course_type', [1, 2])
            ->get();

        return collect($courses)
            ->groupBy('course_type')
            ->mapWithKeys(function ($course_type, $key) {
                return [
                    $key => [
                        'courses' => collect($course_type)
                            ->sortByDesc('total_leads_submitted_to_crm')
                            ->take(3)
                            ->map(function ($course){
                                $institution_details = InstitutionsHelper::summary($course->institution_code);
                                return [
                                    'course_code' => $course->course_code,
                                    'course_small_image' => $course->course_small_image,
                                    'course_image' => $course->course_image,
                                    'institution_name' => isset($institution_details->data->institution_name) && !empty($institution_details->data->institution_name) ? $institution_details->data->institution_name : "",
                                    'total_leads_submitted_to_crm' => CraydelHelperFunctions::shortenNumber($course->total_leads_submitted_to_crm),
                                    'discipline' => DB::table((new AcademicDiscipline())->getTable())->where('id', $course->discipline_code)->value('discipline_name'),
                                    'url_course_slug' => $course->course_code.'-'.$course->course_name_slug,
                                    'course_name' => $course->course_name,
                                    'institution_country' => isset($institution_details->data->country->name) && !empty($institution_details->data->country->name) ? $institution_details->data->country->name : null
                                ];
                            })->reject(function ($course){
                                return !isset($course['course_code']) || empty($course['course_code']);
                            })->sortByDesc('total_leads_submitted_to_crm')
                            ->toArray()
                        ,'total_leads_submitted_to_crm' => $course_type->sum('total_leads_submitted_to_crm')
                    ]
                ];
            })
            ->sortByDesc('total_leads_submitted_to_crm')
            ->toArray();
    }
}
