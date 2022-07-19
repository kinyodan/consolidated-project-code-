<?php

namespace App\Http\Controllers\ManageGraduationYears\Queries;

use App\Http\Controllers\CraydelTypes\CraydelJSONResponseType;
use App\Http\Controllers\Helpers\CraydelHelperFunctions;
use App\Http\Controllers\Helpers\DateHelper;
use App\Http\Controllers\Helpers\LanguageTranslationHelper;
use App\Http\Controllers\Traits\CanCache;
use App\Http\Controllers\Traits\CanLog;
use App\Http\Controllers\Traits\CanPaginate;
use App\Http\Controllers\Traits\CanRespond;
use App\Models\GraduationYear;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;


class ShowGraduationYearsQueryController
{
  use CanCache, CanRespond, CanLog, CanPaginate;
  
  /**
   * Handle the classes list quest
   *
   * @param Request $request
   * @param $year_id
   * @return JsonResponse
   */
  public function handle(Request $request,$year_id): JsonResponse
  {
    try {
      $year = GraduationYear::where('is_active', '=', 1)->where('id',$year_id)->get();
      
      return $this->respondInJSON(new CraydelJSONResponseType(
        true,
        LanguageTranslationHelper::translate('classes.success.listed'),
        $year
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