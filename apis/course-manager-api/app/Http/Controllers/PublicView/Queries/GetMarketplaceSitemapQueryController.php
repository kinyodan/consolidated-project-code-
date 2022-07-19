<?php
namespace App\Http\Controllers\PublicView\Queries;

use App\Http\Controllers\CraydelTypes\CraydelJSONResponseType;
use App\Http\Controllers\Helpers\AcademicDisciplineHelper;
use App\Http\Controllers\Helpers\CraydelHelperFunctions;
use App\Http\Controllers\Helpers\InstitutionsHelper;
use App\Http\Controllers\Helpers\RequestHelper;
use App\Http\Controllers\PublicView\CoursesPublicViewController;
use App\Http\Controllers\Traits\CanCache;
use App\Models\Course;
use Exception;
use Illuminate\Http\JsonResponse;

class GetMarketplaceSitemapQueryController
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
     * Generate the marketplace sitemap
     * @return JsonResponse
    */
    public function generate(): JsonResponse
    {
        try{
            return $this->coursesPublicViewController->respondInJSON(
                new CraydelJSONResponseType(
                    true,
                    'Loaded',
                    array_merge(
                        [],
                        $this->_courseRoutes(),
                        $this->_academicDisciplinesRoutes(),
                        $this->_institutionsRoutes(),
                        $this->_getStudyDestinationRoutes(),
                        $this->_getAllCourseCategoryMenu()
                    )
                )
            );
        }catch (Exception $exception){
            $this->coursesPublicViewController->logException($exception);

            return $this->coursesPublicViewController->respondInJSON(
                new CraydelJSONResponseType(
                    false,
                    $exception->getMessage()
                )
            );
        }
    }

    /**
     * Get courses routes
     *
     * @return array|null
     * @throws Exception
     */
    protected function _courseRoutes(): ?array
    {
        $courses = Course::with(['linkedDisciplines'])
            ->where('is_deleted', 0)
            ->where('is_active', 1)
            ->get();

        if(count($courses) <= 0){
            throw new Exception("Could not find active courses");
        }

        $course_routes = [];

        foreach ($courses as $course){
            $disciplines = $course->linkedDisciplines ?? array();

            foreach ($disciplines as $key => $discipline){
                if(isset($discipline->discipline_name)){
                    $slug = CraydelHelperFunctions::slugifyString($discipline->discipline_name);
                    $course_routes[] = "/courses/{$slug}/{$course->course_code}".'-'."{$course->course_name_slug}";
                }
            }
        }


        return collect($course_routes)
            ->map(function ($course){
                return[
                    'slug' => $course
                ];
            })->toArray();
    }

    /**
     * Get academic discipline routes
     *
     * @return array|null
    */
    protected function _academicDisciplinesRoutes(): ?array
    {
        return collect(AcademicDisciplineHelper::disciplines())
            ->map(function ($discipline){
                return (object)[
                    'discipline_name' => CraydelHelperFunctions::slugifyString($discipline->discipline_name)
                ];
            })
            ->reject(function ($discipline){
                return is_null($discipline->discipline_name);
            })
            ->map(function ($discipline){
                return [
                    'slug' => "/courses/{$discipline->discipline_name}"
                ];
            })
            ->toArray();
    }

    /**
     * Get institution routes
     * @return array|null
    */
    protected function _institutionsRoutes(): ?array
    {
        $institutions = InstitutionsHelper::institutions()->data;

        return collect($institutions)->map(function ($institution){
            return (object)[
                'continent' => isset($institution->country->continent) && !empty($institution->country->continent) ? CraydelHelperFunctions::slugifyString($institution->country->continent) : null,
                'institution_type' => call_user_func(function () use($institution){
                    $institution_type_name = isset($institution->institution_type_name) && !empty($institution->institution_type_name) ? CraydelHelperFunctions::slugifyString($institution->institution_type_name) : null;
                    if(is_null($institution_type_name)){
                        return null;
                    }else{
                        if($institution_type_name == 'university'){
                            return "university";
                        }else{
                            return 'college';
                        }
                    }
                }),
                'country' => isset($institution->country_name) && !empty($institution->country_name) ? CraydelHelperFunctions::slugifyString($institution->country_name) : null,
                'institution_name' => isset($institution->institution_name) && !empty($institution->institution_name) ? CraydelHelperFunctions::slugifyString($institution->institution_name) : null,
                'institution_code' => isset($institution->institution_code) && !empty($institution->institution_code) ? CraydelHelperFunctions::toCleanString($institution->institution_code) : null
            ];
        })->reject(function ($institution){
            if(is_null($institution->continent) || is_null($institution->institution_type) || is_null($institution->country) || is_null($institution->institution_name) || is_null($institution->institution_code)){
                return true;
            }
            return false;
        })->map(function ($institution){
            return [
                'slug' => "/institutions/{$institution->continent}/{$institution->institution_type}/{$institution->country}/{$institution->institution_name}?institution_code={$institution->institution_code}"
            ];
        })->toArray();
    }

    /**
     * Get the study destination sitemap slugs
     * @return array|null
     * @throws Exception
     */
    protected function _getStudyDestinationRoutes(): ?array
    {
        $data = RequestHelper::get('https://campaigns.craydel.com/wp-json/craydel/v2/page-titles');
        $destinations = json_decode($data->data);

        return collect($destinations)
            ->reject(function ($destination){
                return empty($destination->slug);
            })->map(function ($destination){
                return [
                    'slug' => "/study-abroad/{$destination->slug}"
                ];
            })->toArray();
    }

    /**
     * Get the top courses pages
     *
     * @return array|null
     * @throws Exception
     */
    protected function _getAllCourseCategoryMenu(): ?array
    {
        $data = RequestHelper::get('https://campaigns.craydel.com/wp-json/craydel/v2/menu/24');
        $all_course_category_menu = json_decode($data->data);
        $all_course_category_menu_children = [];

        foreach ($all_course_category_menu as $category_menu){
            if(isset($category_menu->children)){
                foreach ($category_menu->children as $child){
                    if(isset($child->url) && filter_var($child->url, FILTER_VALIDATE_URL)){
                        $url = parse_url($child->url);

                        if(isset($url['path'])){
                            $all_course_category_menu_children[] = call_user_func(function () use($url){
                                if(isset($url['query']) && !empty($url['query'])){
                                    return $url['path'].'?'.$url['query'];
                                }else{
                                    return $url['path'];
                                }
                            });
                        }
                    }
                }
            }
        }

        return collect($all_course_category_menu_children)
            ->reject(function ($menu){
                return empty($menu);
            })->map(function ($menu){
                return [
                    'slug' => $menu
                ];
            })->toArray();
    }
}
