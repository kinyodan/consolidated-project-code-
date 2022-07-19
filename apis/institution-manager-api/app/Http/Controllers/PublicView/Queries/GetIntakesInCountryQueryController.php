<?php
namespace App\Http\Controllers\PublicView\Queries;

use App\Http\Controllers\CraydelTypes\CraydelJSONResponseType;
use App\Http\Controllers\Helpers\CraydelHelperFunctions;
use App\Http\Controllers\Traits\CanCache;
use App\Http\Controllers\Traits\CanRespond;
use App\Models\CountryIntake;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class GetIntakesInCountryQueryController
{
    use CanCache, CanRespond;

    /**
     * Get countries with active programs
     *
     * @param string $country_code
     * @return JsonResponse
     */
    public function get(string $country_code): JsonResponse
    {
        try{
            $country_code = strtoupper(CraydelHelperFunctions::toCleanString($country_code));

            if(CraydelHelperFunctions::isNull($country_code)){
                throw new Exception("Missing country code");
            }

            if(!DB::table((new CountryIntake())->getTable())->where('country_code', $country_code)->exists()){
                throw new Exception("Invalid country code");
            }

            $intakes = self::cache('program_intakes_in_'.$country_code);

            if(is_null($intakes)){
                $intakes = self::cache('program_intakes_in_'.$country_code, call_user_func(function () use($country_code){
                    return DB::table((new CountryIntake())->getTable())
                        ->where('country_code', $country_code)
                        ->select(['month_name'])
                        ->distinct()
                        ->orderBy('month_name')
                        ->get()
                        ->toArray();
                }));
            }

            $months = [];

            foreach ($intakes as $intake){
                if(isset($intake->month_name) && !CraydelHelperFunctions::isNull($intake->month_name)){
                    $months[] = $intake->month_name;
                }
            }

            $months = CraydelHelperFunctions::orderMonthNames($months);

            return $this->respondInJSON(new CraydelJSONResponseType(
                true,
                'Listed',
                collect($months)->map(function ($month){
                    return [
                        'intake' => CraydelHelperFunctions::toCleanString($month),
                    ];
                })->toArray()
            ));
        }catch (Exception $exception){
            return $this->respondInJSON(new CraydelJSONResponseType(
                false,
                $exception->getMessage()
            ));
        }
    }
}
