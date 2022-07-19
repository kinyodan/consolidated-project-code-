<?php

namespace App\Http\Controllers\ManageSchools\Queries;

use App\Http\Controllers\CraydelTypes\CraydelJSONResponseType;
use App\Http\Controllers\Helpers\CraydelHelperFunctions;
use App\Http\Controllers\Helpers\DateHelper;
use App\Http\Controllers\Helpers\LanguageTranslationHelper;
use App\Http\Controllers\Traits\CanCache;
use App\Http\Controllers\Traits\CanLog;
use App\Http\Controllers\Traits\CanPaginate;
use App\Http\Controllers\Traits\CanRespond;
use App\Models\GraduationYear;
use App\Models\School;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;


class ShowSchoolDetailsQueryController
{
  use CanCache, CanRespond, CanLog, CanPaginate;

  /**
   * Handle the classes list quest
   *
   * @param Request $request
   * @param $school_id
   * @return JsonResponse
   */
  public function handle(Request $request,$school_id): JsonResponse
  {
    try {
      $school = School::where('status', '=', 1)->where('id',$school_id)->get();

      return $this->respondInJSON(new CraydelJSONResponseType(
        true,
        LanguageTranslationHelper::translate('schools.success.listed'),
        $school
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
