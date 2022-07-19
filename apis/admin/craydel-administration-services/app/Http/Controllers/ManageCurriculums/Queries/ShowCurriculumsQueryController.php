<?php

namespace App\Http\Controllers\ManageCurriculums\Queries;

use App\Http\Controllers\CraydelTypes\CraydelJSONResponseType;
use App\Http\Controllers\Helpers\CraydelHelperFunctions;
use App\Http\Controllers\Helpers\DateHelper;
use App\Http\Controllers\Helpers\LanguageTranslationHelper;
use App\Http\Controllers\Traits\CanCache;
use App\Http\Controllers\Traits\CanLog;
use App\Http\Controllers\Traits\CanPaginate;
use App\Http\Controllers\Traits\CanRespond;
use App\Models\Curriculum;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;


class ShowCurriculumsQueryController
{
  use CanCache, CanRespond, CanLog, CanPaginate;
  
  /**
   * Handle the classes list quest
   *
   * @param Request $request
   * @return JsonResponse
   */
  public function handle(Request $request,$curriculums_id): JsonResponse
  {
    try {
      $curriculum = Curriculum::where('is_active', '=', 1)->where('id',$curriculums_id)->get();
      
      return $this->respondInJSON(new CraydelJSONResponseType(
        true,
        LanguageTranslationHelper::translate('curriculum.success.listed'),
        $curriculum
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
