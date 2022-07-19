<?php

namespace App\Http\Controllers\Helpers;

use App\Http\Controllers\Traits\CanLog;
use App\Http\Controllers\Traits\CanCache;
use App\Http\Controllers\Traits\CanRespond;
use App\Models\Country;
use Illuminate\Support\Facades\DB;


class CountryHelper
{
  
  use CanLog, CanCache, CanRespond;
  
  public static function countries(): ?array
  {
    
    try {
      $countries = self::cache('_countries_list');
      if (is_null($countries)) {
        $countries = DB::table((new Country())->getTable())
          ->orderBy('id')
          ->get([
            'id', 'code', 'name'
          ])->toArray();
        
        self::cache('_countries_list', $countries);
      }
      return $countries;
    } catch (\Exception $exception) {
      (new self())->logException($exception);
      return null;
    }
  }
  
  public static function getCountryId($country_code): ?int
  {
    
    try {
      $country = DB::table((new Country())->getTable())
        ->where('code', '=', $country_code)
        ->first();
      return $country->id;
      
    } catch (\Exception $exception) {
      (new self())->logException($exception);
      return null;
    }
  }
  
  
}