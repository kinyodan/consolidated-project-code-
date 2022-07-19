<?php

namespace App\Http\Controllers\ManageSchoolBankDetails\Queries;

use App\Http\Controllers\CraydelTypes\CraydelJSONResponseType;
use App\Http\Controllers\Helpers\GetLoggedIUserHelper;
use App\Http\Controllers\Helpers\LanguageTranslationHelper;
use App\Http\Controllers\Traits\CanCache;
use App\Http\Controllers\Traits\CanLog;
use App\Http\Controllers\Traits\CanPaginate;
use App\Http\Controllers\Traits\CanRespond;
use App\Models\SchoolBankDetail;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;

class ShowSchoolBankDetailsQueryController
{
  use CanCache, CanRespond, CanLog, CanPaginate;
  
  /**
   * Handle the classes list quest
   *
   * @param Request $request
   * @param string $school_code
   * @return JsonResponse
   */
  public function handle(Request $request, string $school_code): JsonResponse
  {
    try {
      
      $school = GetLoggedIUserHelper::getUserSchool($request, $school_code);
      
      $school_bank_details = DB::table((new SchoolBankDetail())->getTable())
        ->where('status', 1)
        ->where('school_id', $school->id)
        ->get();
      
      return $this->respondInJSON(new CraydelJSONResponseType(
        true,
        LanguageTranslationHelper::translate('streams.success.listed'),
        (object)
          [
            'item' => $school_bank_details
          ]
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
