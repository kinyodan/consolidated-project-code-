<?php
namespace App\Http\Controllers\Courses\Commands;

use App\Http\Controllers\Helpers\CraydelHelperFunctions;
use App\Models\CourseAcademicDiscipline;
use Illuminate\Support\Facades\DB;
use PHPUnit\Util\Exception;

class LinkCourseToAcademicDisciplineCommandController
{
    /**
     * Link course to the academic discipline
     *
     * @param int $courses_id
     * @param $academic_discipline_ids
     */
    public static function link(int $courses_id, $academic_discipline_ids){
        if(empty($courses_id)){
            throw new Exception("Invalid course code.");
        }

        if(CraydelHelperFunctions::isJson($academic_discipline_ids)){
            $academic_discipline_ids = json_decode($academic_discipline_ids);
        }

        if(!is_numeric($academic_discipline_ids) && !is_array($academic_discipline_ids)){
            throw new Exception("Invalid academic discipline ids value.");
        }

        if(is_numeric($academic_discipline_ids)){
            DB::transaction(function () use($courses_id, $academic_discipline_ids){
                DB::table((new CourseAcademicDiscipline())->getTable())
                    ->where('courses_id', $courses_id)
                    ->delete();

                DB::table((new CourseAcademicDiscipline())->getTable())
                    ->insertOrIgnore([
                        'courses_id' => $courses_id,
                        'academic_discipline_id' => $academic_discipline_ids
                    ]);
            });
        }

        if(is_array($academic_discipline_ids)){
            DB::table((new CourseAcademicDiscipline())->getTable())
                ->where('courses_id', $courses_id)
                ->delete();

            for ($i = 0; $i <= (count($academic_discipline_ids) - 1); $i++){
                if(isset($academic_discipline_ids[$i]) && !CraydelHelperFunctions::isNull($academic_discipline_ids[$i])){
                    DB::table((new CourseAcademicDiscipline())->getTable())
                        ->insertOrIgnore([
                            'courses_id' => $courses_id,
                            'academic_disciplines_id' => $academic_discipline_ids[$i]
                        ]);
                }
            }
        }
    }
}
