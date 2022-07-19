<?php

namespace App\Http\Controllers\Helpers;

use App\Http\Controllers\Traits\CanLog;
use App\Http\Controllers\Traits\CanCache;
use App\Http\Controllers\Traits\CanRespond;
use App\Models\GraduationYear;
use Illuminate\Support\Facades\DB;


class YearHelper
{
  
  use CanLog, CanCache, CanRespond;
  
  public static function years(): ?array
  {
    
    try {
      $years = self::cache('_years_list');
      if (is_null($years)) {
        $years = DB::table((new GraduationYear())->getTable())
          ->orderBy('id')
          ->get([
            'id', 'year', 'description'
          ])->toArray();
        
        self::cache('_years_list', $years);
      }
      return $years;
    } catch (\Exception $exception) {
      (new self())->logException($exception);
      return null;
    }
  }
  
  
}