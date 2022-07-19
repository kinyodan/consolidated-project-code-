<?php
namespace App\Http\Controllers\Helpers;

use App\Http\Controllers\Traits\CanCache;
use App\Models\Countries;
use Illuminate\Support\Facades\DB;

class CountryHelper
{
    use CanCache;

    /**
     * Get country name from country Code
     * @param string|null $country_code
     * @return string|null
     */
    public static function getName(?string $country_code): ?string
    {
        $country_code = CraydelHelperFunctions::toCleanString($country_code);

        if(CraydelHelperFunctions::isNull($country_code)){
            return null;
        }

        $country_name = self::cache("country_name_from_code_" . $country_code);

        if(empty($country_name)){
            $country_name = DB::table((new Countries())->getTable())
                ->where('code', strtoupper($country_code))
                ->value('name');

            if(empty($country_name)){
                if(DB::table((new Countries())->getTable())->where('name', $country_code)->exists()){
                    return self::cache("country_name_from_code_" . $country_code, $country_code);
                }else{
                    return null;
                }
            }else{
                return self::cache("country_name_from_code_" . $country_code, $country_name);
            }
        }else{
            return $country_name;
        }
    }

    /**
     * Get country id from country Code
     * @param string|null $country_code
     * @return string|null
     */
    public static function getIDBasedOnCode(?string $country_code): ?string
    {
        $country_code = strtoupper(CraydelHelperFunctions::toCleanString($country_code));

        if(CraydelHelperFunctions::isNull($country_code)){
            return null;
        }

        $country_id = self::cache("country_id_from_code_" . $country_code);

        if(empty($country_id)){
            $country_id = DB::table((new Countries())->getTable())
                ->where('code', strtoupper($country_code))
                ->value('id');

            if(empty($country_id)){
                return null;
            }else{
                return self::cache("country_id_from_code_" . $country_code, $country_id);
            }
        }else{
            return $country_id;
        }
    }

    /**
     * Get country code from country id
     * @param string|null $country_id
     * @return string|null
     */
    public static function getCountryCodeFromID(?string $country_id): ?string
    {
        $country_id = CraydelHelperFunctions::toNumbers($country_id);

        if(CraydelHelperFunctions::isNull($country_id)){
            return null;
        }

        $country_code = self::cache("country_code_from_id_" . $country_id);

        if(empty($country_code)){
            $country_code = DB::table((new Countries())->getTable())
                ->where('id', $country_id)
                ->value('code');

            if(empty($country_code)){
                return null;
            }else{
                return self::cache("country_code_from_id_" . $country_id, $country_code);
            }
        }else{
            return $country_code;
        }
    }
}
