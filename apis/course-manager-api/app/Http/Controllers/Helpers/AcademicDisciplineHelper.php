<?php
namespace App\Http\Controllers\Helpers;

use App\Http\Controllers\Traits\CanCache;
use App\Http\Controllers\Traits\CanLog;
use App\Models\AcademicDiscipline;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class AcademicDisciplineHelper
{
    use CanCache, CanLog;

    /**
     * Get Academic Disciplines
    */
    public static function disciplines(): ?array
    {
        try{
            $academicDisciplines = self::cache('academic_disciplines_list');

            if(is_null($academicDisciplines)){
                $academicDisciplines = DB::table((new AcademicDiscipline())->getTable())
                    ->where('is_deleted', '=',0)
                    ->where('status', '=',1)
                    ->orderBy('discipline_name')
                    ->get([
                        'id', 'discipline_code', 'discipline_name'
                    ])->toArray();

                self::cache('academic_disciplines_list', $academicDisciplines);
            }

            return $academicDisciplines;
        }catch (Exception $exception){
            (new self())->logException($exception);
            return null;
        }
    }

    /**
     * Get discipline
     *
     * @param string|null $discipline_code
     * @return mixed|null
     */
    public static function getDisciplineNameByCode(?string $discipline_code){
        try{
            $discipline_code = CraydelHelperFunctions::toCleanString($discipline_code);

            if(is_null($discipline_code) || empty($discipline_code)){
                return null;
            }

            $discipline_name = self::cache(
                CraydelHelperFunctions::slugifyString('course_discipline_name_by_code_'.$discipline_code)
            );

            if(!empty($discipline_name)){
                return $discipline_name;
            }

            $academic_discipline = DB::table((new AcademicDiscipline())->getTable())->where('discipline_code', $discipline_code)->first(['discipline_name']);

            if(isset($academic_discipline->discipline_name) && !empty($academic_discipline->discipline_name)){
                return self::cache(CraydelHelperFunctions::slugifyString('course_discipline_name_by_code_'.$discipline_code), $academic_discipline->discipline_name);
            }

            return null;
        }catch (Exception $exception){
            (new self())->logException($exception);
            return null;
        }
    }

    /**
     * Get discipline by discipline ID
     *
     * @param string|null $discipline_code
     * @return mixed|null
     */
    public static function getDisciplineNameByID(?string $discipline_code){
        try{
            $discipline_code = CraydelHelperFunctions::toCleanString($discipline_code);

            if(is_null($discipline_code) || empty($discipline_code)){
                return null;
            }

            $discipline_name = self::cache(
                CraydelHelperFunctions::slugifyString('course_discipline_name_by_id_'.$discipline_code)
            );

            if(!empty($discipline_name)){
                return $discipline_name;
            }

            $discipline_name = DB::table((new AcademicDiscipline())->getTable())
                ->where('id', $discipline_code)
                ->value('discipline_name');

            if(!empty($discipline_name)){
                return self::cache(CraydelHelperFunctions::slugifyString('course_discipline_name_by_id_'.$discipline_code), $discipline_name);
            }

            return null;
        }catch (Exception $exception){
            (new self())->logException($exception);
            return null;
        }
    }

    /**
     * Get the course discipline by name
     *
     * @param string|null $discipline_name
     * @return mixed|null
     */
    public static function getDisciplineIDByName(?string $discipline_name){
        try{
            $discipline_code = CraydelHelperFunctions::toCleanString($discipline_name);

            if(is_null($discipline_name) || empty($discipline_name)){
                return null;
            }

            $discipline_id = self::cache(
                CraydelHelperFunctions::slugifyString('course_discipline_id_by_name_'.$discipline_name)
            );

            if(!empty($discipline_id)){
                return $discipline_id;
            }

            $discipline_id = DB::table((new AcademicDiscipline())->getTable())
                ->where('discipline_name', trim($discipline_name))
                ->value('id');

            if(!empty($discipline_id)){
                return self::cache(
                    CraydelHelperFunctions::slugifyString('course_discipline_id_by_name_'.$discipline_name),
                    $discipline_id
                );
            }

            return null;
        }catch (Exception $exception){
            (new self())->logException($exception);
            return null;
        }
    }

    /**
     * Get discipline code by discipline ID
     *
     * @param int|null $discipline_id
     * @return mixed|null
     */
    public static function getDisciplineCodeByID(?int $discipline_id){
        try{
            $discipline_id = CraydelHelperFunctions::toNumbers($discipline_id);

            if(CraydelHelperFunctions::isNull($discipline_id)){
                return null;
            }

            $discipline_code = self::cache(
                CraydelHelperFunctions::slugifyString('course_discipline_code_by_id_'.$discipline_id)
            );

            if(!empty($discipline_code)){
                return $discipline_code;
            }

            $discipline_code = DB::table((new AcademicDiscipline())->getTable())
                ->where('id', $discipline_id)
                ->value('discipline_code');

            if(!empty($discipline_code)){
                return self::cache(CraydelHelperFunctions::slugifyString('course_discipline_code_by_id_'.$discipline_id), $discipline_code);
            }

            return null;
        }catch (Exception $exception){
            (new self())->logException($exception);
            return null;
        }
    }

    /**
     * Get discipline code by discipline ID
     *
     * @param int|null $discipline_code
     * @return mixed|null
     */
    public static function getDisciplineIDByCode(?int $discipline_code){
        try{
            $discipline_code = CraydelHelperFunctions::toCleanString($discipline_code);

            if(CraydelHelperFunctions::isNull($discipline_code)){
                return null;
            }

            $discipline_id = self::cache(
                CraydelHelperFunctions::slugifyString('course_discipline_id_by_code_'.$discipline_code)
            );

            if(!empty($discipline_id)){
                return intval($discipline_id);
            }

            $discipline_id = DB::table((new AcademicDiscipline())->getTable())
                ->where('discipline_code', $discipline_code)
                ->value('id');

            if(!empty($discipline_id)){
                return intval(self::cache(CraydelHelperFunctions::slugifyString('course_discipline_id_by_code_'.$discipline_code), $discipline_id));
            }

            return null;
        }catch (Exception $exception){
            (new self())->logException($exception);
            return null;
        }
    }
}
