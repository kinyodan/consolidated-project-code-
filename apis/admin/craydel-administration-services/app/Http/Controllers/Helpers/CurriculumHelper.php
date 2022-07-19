<?php

namespace App\Http\Controllers\Helpers;

use App\Http\Controllers\Traits\CanLog;
use App\Http\Controllers\Traits\CanCache;
use App\Http\Controllers\Traits\CanRespond;
use App\Models\Curriculum;
use Illuminate\Support\Facades\DB;


class CurriculumHelper
{
  
  use CanLog, CanCache, CanRespond;
  
  public static function curriculums(): ?array
  {
    
    try {
      $curriculums = self::cache('_curriculums_list');
      if (is_null($curriculums)) {
        $curriculums = DB::table((new Curriculum())->getTable())
          ->orderBy('id')
          ->get([
            'id', 'country_code', 'curriculum_name', 'curriculum_code'
          ])->toArray();
        
        self::cache('_curriculums_list', $curriculums);
      }
      return $curriculums;
    } catch (\Exception $exception) {
      (new self())->logException($exception);
      return null;
    }
  }
  
  
}