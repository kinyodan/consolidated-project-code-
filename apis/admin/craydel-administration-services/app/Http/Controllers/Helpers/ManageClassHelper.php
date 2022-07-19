<?php

namespace App\Http\Controllers\Helpers;

use App\Http\Controllers\Traits\CanLog;
use App\Http\Controllers\Traits\CanCache;
use App\Http\Controllers\Traits\CanRespond;
use App\Models\UniversalSchoolClass;


class ManageClassHelper
{
  
  use CanLog, CanCache, CanRespond;
  
  public static function classes(): ?array
  {
    
    try {
      $classes = self::cache('_classes_list');
      if (is_null($classes)) {
        $classes = UniversalSchoolClass::where('universal_school_classes.is_active', '=', 1)
          ->join('curriculums', 'curriculums.id', '=', 'universal_school_classes.curriculum_id')
          ->select('universal_school_classes.id','universal_school_classes.curriculum_id','universal_school_classes.class_name','curriculums.country_code as country_code', 'curriculums.curriculum_code')
          ->get()->toArray();;
        
        self::cache('_classes_list', $classes);
      }
      return $classes;
    } catch (\Exception $exception) {
      (new self())->logException($exception);
      return null;
    }
  }
  
  
}