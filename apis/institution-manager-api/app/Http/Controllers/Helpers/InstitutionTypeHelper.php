<?php
namespace App\Http\Controllers\Helpers;

use App\Http\Controllers\Traits\CanCache;
use App\Http\Controllers\Traits\CanLog;
use App\Models\Country;
use App\Models\InstitutionType;
use Illuminate\Support\Facades\DB;

class InstitutionTypeHelper
{
    use CanLog, CanCache;

    /**
     * View allowed countries
    */
    public static function types(): ?array
    {
        try {
            $institutionTypes = self::cache('institution_types_list');

            if(is_null($institutionTypes)){
                $institutionTypes = DB::table((new InstitutionType())->getTable())
                    ->where('is_deleted', 0)
                    ->where('is_blocked', 0)
                    ->get([
                        'id', 'name'
                    ])->toArray();

                self::cache('institution_types_list', $institutionTypes);
            }

            return $institutionTypes;
        }catch (\Exception $exception){
            (new self())->logException($exception);
            return null;
        }
    }

    /**
     * Get the country ISO code from the country name
     *
     * @param string|null $name
     * @param int|null $default_value
     *
     * @return string
     */
    public static function getInstitutionTypeIdFromName(?string $name, ?int $default_value = 1): ?string
    {
        try{
            if(empty($name)){
                return null;
            }

            $id = DB::table((new InstitutionType())->getTable())
                ->where('name', trim($name))
                ->value('id');

            return !is_null($id) ? trim($id) : $default_value;
        }catch (\Exception $exception){
            (new self())->logException($exception);
            return null;
        }
    }
    public static function getInstitutionCodeFromName(?string $name, ?int $default_value = 1): ?string
    {
        try{
            if(empty($name)){
                return null;
            }

            $id = DB::table('institutions')
                ->where('institution_name', trim($name))
                ->value('institution_code');

            return !is_null($id) ? trim($id) : $default_value;
        }catch (\Exception $exception){
            (new self())->logException($exception);
            return null;
        }
    }
}
