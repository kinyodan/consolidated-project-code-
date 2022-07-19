<?php
namespace App\Http\Controllers\Helpers;

use App\Http\Controllers\CraydelTypes\CraydelCourseType;
use App\Http\Controllers\Traits\CanCache;
use App\Http\Controllers\Traits\CanLog;
use App\Models\CourseType;
use Illuminate\Support\Facades\DB;

class CourseTypesHelper
{
    use CanCache, CanLog;

    /**
     * Get course types
    */
    public static function types(): ?array
    {
        try{
            $courseTypes = self::cache('course_type_list');

            if(is_null($courseTypes)){
                $courseTypes = DB::table((new CourseType())->getTable())
                    ->where('is_deleted', 0)
                    ->where('is_blocked', 0)
                    ->orderBy('name')
                    ->get([
                        'id', 'name'
                    ])->toArray();

                self::cache('course_type_list', $courseTypes);
            }

            return $courseTypes;
        }catch (\Exception $exception){
            (new self())->logException($exception);
            return null;
        }
    }

    /**
     * Get course type by name
     *
     * @param ?string $course_type_name
     *
     * @return int
    */
    public static function getCourseTypeIDByName(?string $course_type_name): ?int
    {
        try{
            $course_type_name = CraydelHelperFunctions::toCleanString($course_type_name);

            if(is_null($course_type_name) || empty($course_type_name)){
                return null;
            }

            $course_type_id = self::cache(
                CraydelHelperFunctions::slugifyString('course_type_id_by_name'.$course_type_name)
            );

            if(!empty($course_type_id)){
                return $course_type_id;
            }

            $course_type = DB::table((new CourseType())->getTable())->where('slug', CraydelHelperFunctions::slugifyString($course_type_name))->first(['id']);

            if(isset($course_type->id) && !empty($course_type->id)){
                return self::cache(CraydelHelperFunctions::slugifyString('course_type_id_by_name'.$course_type_name), $course_type->id);
            }

            return null;
        }catch (\Exception $exception){
            (new self())->logException($exception);
            return null;
        }
    }

    /**
     * Get course type by name
     *
     * @param int|null $course_type_id
     *
     * @return string
     */
    public static function getCourseTypeNameByID(?int $course_type_id): ?string
    {
        try{
            $course_type_id = CraydelHelperFunctions::toCleanString($course_type_id);

            if(is_null($course_type_id) || empty($course_type_id)){
                return null;
            }

            $course_type_name = self::cache(
                CraydelHelperFunctions::slugifyString('course_type_name_from_id_'.$course_type_id)
            );

            if(!empty($course_type_name)){
                return $course_type_name;
            }

            $course_type = DB::table((new CourseType())->getTable())->where('id', $course_type_id)->first(['name']);

            if(isset($course_type->name) && !empty($course_type->name)){
                return self::cache(CraydelHelperFunctions::slugifyString('course_type_name_from_id_'.$course_type_id), $course_type->name);
            }

            return null;
        }catch (\Exception $exception){
            (new self())->logException($exception);
            return null;
        }
    }
}
