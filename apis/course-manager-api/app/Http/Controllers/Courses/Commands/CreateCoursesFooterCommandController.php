<?php
namespace App\Http\Controllers\Courses\Commands;

use App\Http\Controllers\Helpers\AcademicDisciplineHelper;
use App\Http\Controllers\Helpers\CraydelHelperFunctions;
use App\Http\Controllers\Helpers\CraydelInternalResponseHelper;
use App\Http\Controllers\Helpers\InstitutionsHelper;
use App\Http\Controllers\Traits\CanCache;
use App\Http\Controllers\Traits\CanLog;
use App\Models\Course;
use Illuminate\Support\Facades\DB;
use PHPUnit\Util\Exception;

class CreateCoursesFooterCommandController
{
    use CanCache, CanLog;

    /**
     * @var CraydelInternalResponseHelper $countries
    */
    protected CraydelInternalResponseHelper $countries;

    /**
     * @var array|null $academic_disciplines
    */
    protected ?array $academic_disciplines;

    /**
     * @var string $country_route_format
    */
    public static string $country_route_format = "top-%s-courses-in-%s";

    /**
     * @var string $global_route_format
    */
    public static string $global_route_format = "top-%s-courses";

    /**
     * Construct
    */
    public function __construct()
    {
        $this->countries = InstitutionsHelper::countries();
        $this->academic_disciplines = AcademicDisciplineHelper::disciplines();
    }

    /**
     * Create footer
     *
     * @return void
    */
    public function make(){
        try{
            $custom_footer_menu = (new CustomFilterCommandController())::makeURL();

            foreach ($this->countries->data as $country){
                if (isset($country->name) && !empty($country->name)){
                    $this->logMessage("Generating country footer cache: {$country->name}");
                    $country_footer = array_merge(
                        $this->footer($country->name, self::$country_route_format),
                        $custom_footer_menu
                    );

                    self::cache(
                        CraydelHelperFunctions::slugifyString($country->name.'_footer'),
                        $country_footer
                    );
                }
            }

            $this->logMessage("Generating global footer cache");
            $global_footer = array_merge(
                $this->footer('global', self::$global_route_format),
                $custom_footer_menu
            );

            self::cache(
                CraydelHelperFunctions::slugifyString('global_footer'),
                $global_footer
            );
        }catch (\Exception $exception){
            $this->logException($exception);
        }
    }

    /**
     * Make country footer
     *
     * @param string|null $country_name
     * @param string|null $format
     *
     * @return array
     */
    protected function footer(?string $country_name, ?string $format): array
    {
        try{
            if(empty($country_name)){
                throw new Exception("Missing country name.");
            }

            if(empty($format)){
                throw new Exception("Missing route format.");
            }

            $menu = [];

            foreach ($this->academic_disciplines as $discipline){
                if(isset($discipline->discipline_name) && !empty($discipline->discipline_name)){
                    array_push(
                        $menu,
                        call_user_func(function () use($format, $discipline, $country_name){
                            if($format == self::$global_route_format){
                                if(DB::table((new Course())->getTable())->where('is_active', 1)->where('discipline_code', $discipline->id)->whereNotNull('indexing_object_id')->count('id') > 0){
                                    return [
                                        'name' => ucwords(strtolower($discipline->discipline_name)),
                                        'slug' => sprintf(
                                            self::$global_route_format,
                                            CraydelHelperFunctions::hyphenatedSlug($discipline->discipline_name)
                                        )
                                    ];
                                }else{
                                    return null;
                                }
                            }else{
                                if(DB::table((new Course())->getTable())->where('is_active', 1)->where('discipline_code', $discipline->id)->whereNotNull('indexing_object_id')->count('id') > 0){
                                    return [
                                        'name' => ucwords(strtolower($discipline->discipline_name)),
                                        'slug' => sprintf(
                                            self::$country_route_format,
                                            CraydelHelperFunctions::hyphenatedSlug($discipline->discipline_name),
                                            CraydelHelperFunctions::hyphenatedSlug($country_name)
                                        )
                                    ];
                                }else{
                                    return null;
                                }
                            }
                        })
                    );
                }
            }

            return collect($menu)->reject(function ($item){
                return is_null($item);
            })->values()->toArray();
        }catch (\Exception $exception){
            $this->logException($exception);
            return array();
        }
    }
}
