<?php

namespace App\Http\Controllers\ManageClasses\Queries;

use App\Http\Controllers\CraydelTypes\CraydelJSONResponseType;
use App\Http\Controllers\Helpers\CraydelHelperFunctions;
use App\Http\Controllers\Helpers\DateHelper;
use App\Http\Controllers\Helpers\LanguageTranslationHelper;
use App\Http\Controllers\Traits\CanCache;
use App\Http\Controllers\Traits\CanLog;
use App\Http\Controllers\Traits\CanPaginate;
use App\Http\Controllers\Traits\CanRespond;
use App\Models\UniversalSchoolClass;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;


class ShowClassQueryController
{
  use CanCache, CanRespond, CanLog, CanPaginate;
  
  /**
   * Handle the classes list quest
   *
   * @param Request $request
   * @param $class_id
   * @return JsonResponse
   */
  public function handle(Request $request,$class_id): JsonResponse
  {
    try {
      
      $class = UniversalSchoolClass::where('universal_school_classes.is_active', '=', 1)
        ->where('universal_school_classes.id','=',$class_id)
        ->join('curriculums', 'curriculums.id', '=', 'universal_school_classes.curriculum_id')
        ->select('universal_school_classes.*', 'curriculums.country_code as country_code','curriculums.curriculum_code')
        ->get();
      return $this->respondInJSON(new CraydelJSONResponseType(
        true,
        LanguageTranslationHelper::translate('classes.success.listed'),
        $class
      ));
      
    } catch (Exception $exception) {
      self::logException($exception);
      
      return $this->respondInJSON(new CraydelJSONResponseType(
        false,
        $exception->getMessage()
      ));
    }
  }
}
