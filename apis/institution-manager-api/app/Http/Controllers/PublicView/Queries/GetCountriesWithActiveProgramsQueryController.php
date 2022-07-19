<?php
namespace App\Http\Controllers\PublicView\Queries;

use App\Http\Controllers\CraydelTypes\CraydelJSONResponseType;
use App\Http\Controllers\Traits\CanCache;
use App\Http\Controllers\Traits\CanRespond;
use App\Models\CountryIntake;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class GetCountriesWithActiveProgramsQueryController
{
    use CanCache, CanRespond;

    /**
     * Get countries with active programs
    */
    public function get(): JsonResponse
    {
        try{
            $countries = self::cache('countries_with_active_programs');

            if(is_null($countries)){
                $countries = self::cache('countries_with_active_programs', call_user_func(function (){
                    return DB::table((new CountryIntake())->getTable())
                        ->select(['country_name', 'country_code'])
                        ->distinct()
                        ->orderBy('country_name')
                        ->get()
                        ->toArray();
                }));
            }

            return $this->respondInJSON(new CraydelJSONResponseType(
                true,
                'Listed',
                $countries
            ));
        }catch (Exception $exception){
            return $this->respondInJSON(new CraydelJSONResponseType(
                false,
                $exception->getMessage()
            ));
        }
    }
}
