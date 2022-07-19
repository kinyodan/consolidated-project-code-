<?php
namespace App\Http\Controllers\Helpers;

use App\Http\Controllers\Traits\CanCache;
use App\Http\Controllers\Traits\CanLog;
use App\Models\LearningMode;
use Illuminate\Support\Facades\DB;
use PHPUnit\Util\Exception;

class LearningModeHelper
{
    use CanCache, CanLog;

    /**
     * Get course types
    */
    public static function modes(): ?array
    {
        try{
            $learningModes = self::cache('learning_modes_list');

            if(is_null($learningModes)){
                $learningModes = DB::table((new LearningMode())->getTable())
                    ->where('is_deleted', 0)
                    ->where('is_blocked', 0)
                    ->get([
                        'id', 'name'
                    ])->toArray();

                self::cache('learning_modes_list', $learningModes);
            }

            return $learningModes;
        }catch (\Exception $exception){
            (new self())->logException($exception);
            return null;
        }
    }

    /**
     * Get learning mode name by id
     *
     * @param $learning_mode_id
     * @return string|null
     */
    public static function getLearningModeByID($learning_mode_id): ?string
    {
        try{
            $learning_mode_id = intval($learning_mode_id);

            if(empty($learning_mode_id)){
                return null;
            }

            /*if(is_string($learning_mode_id)){
                return $learning_mode_id;
            }

            $learning_mode_name = self::cache('learning_mode_name_by_id'.$learning_mode_id);

            if(!is_null($learning_mode_name)){
                return $learning_mode_name;
            }*/

            $learning_mode = DB::table((new LearningMode())->getTable())
                ->where('id', $learning_mode_id)
                ->first(['name']);

            /*if(!is_null($learning_mode_name)){
                self::cache('learning_mode_name_by_id'.$learning_mode_id, $learning_mode_name);
            }*/

            /*return self::cache('learning_mode_name_by_id'.$learning_mode_id);*/

            return isset($learning_mode->name) && !empty($learning_mode->name) ? $learning_mode->name : null;
        }catch (\Exception $exception){
            (new self())->logException($exception);
            return null;
        }
    }

    /**
     * Get the learning mode ID by name
    */
    public static function getLearningModeIDByName(?string $learning_mode_name){
        try{
            if(empty($learning_mode_name)){
                throw new Exception("Missing learning mode name");
            }

            $learning_mode_id = self::cache(
                CraydelHelperFunctions::slugifyString('learning_mode_id_by_name_'.$learning_mode_name)
            );

            if(!is_null($learning_mode_id) && intval($learning_mode_id) > 0){
                return $learning_mode_id;
            }

            $learning_mode_id = DB::table((new LearningMode())->getTable())
                ->where('slug', CraydelHelperFunctions::slugifyString($learning_mode_name))
                ->value('id');

            if(empty($learning_mode_id) || intval($learning_mode_id) <= 0){
                throw new \Exception("Unable to get the learning mode : ".$learning_mode_name." ID ");
            }

            self::cache(
                CraydelHelperFunctions::slugifyString('learning_mode_id_by_name_'.$learning_mode_name),
                $learning_mode_id
            );

            return $learning_mode_id;
        }catch (\Exception $exception){
            (new self())->logException($exception);
            return null;
        }
    }
}
