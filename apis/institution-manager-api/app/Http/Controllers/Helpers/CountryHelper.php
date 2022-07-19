<?php
namespace App\Http\Controllers\Helpers;

use App\Http\Controllers\Traits\CanCache;
use App\Http\Controllers\Traits\CanLog;
use App\Models\Country;
use Illuminate\Support\Facades\DB;

class CountryHelper
{
    use CanLog, CanCache;

    /**
     * View allowed countries
    */
    public static function countries(): ?array
    {
        try {
            $countries = self::cache('countries_list');

            if(is_null($countries)){
                $countries = DB::table((new Country())->getTable())
                    ->where('is_blocked', 0)
                    ->where('is_deleted', 0)
                    ->orderBy('name', 'ASC')
                    ->get([
                        'iso_code', 'name'
                    ])->toArray();

                self::cache('countries_list', $countries);
            }

            return $countries;
        }catch (\Exception $exception){
            (new self())->logException($exception);
            return null;
        }
    }

    /**
     * Get the country ISO code from the country name
     *
     * @param string|null $countryName
     * @param string|null $default_value
     *
     * @return string
     */
    public static function getISOCodeFromCountryName(?string $countryName, ?string $default_value = 'KE'): ?string
    {
        try{
            if(empty($countryName)){
                return null;
            }

            $iso_code = DB::table((new Country())->getTable())
                ->where('name', trim($countryName))
                ->value('iso_code');

            return !is_null($iso_code) ? trim($iso_code) : $default_value;
        }catch (\Exception $exception){
            (new self())->logException($exception);
            return null;
        }
    }
}
