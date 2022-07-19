<?php

namespace App\Http\Controllers\ManageSchoolBankDetails\Queries;

use App\Http\Controllers\CraydelTypes\CraydelJSONResponseType;
use App\Http\Controllers\Helpers\CraydelHelperFunctions;
use App\Http\Controllers\Helpers\DateHelper;
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

class ListSchoolBankDetailsQueryController
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
        ->where('school_id', $school->id);

      $currentPage = $request->input('page');

      $currentPage = !empty($currentPage) ? $currentPage : 1;
      $this->currentPaginationPage = $currentPage;
      $this->totalNumberOfEntities = $school_bank_details->count('id');
      $this->itemsPerPage = config('craydle.items_per_page', 10);

      Paginator::currentPageResolver(function () use ($currentPage) {
        return $currentPage;
      });

      $school_bank_details = $school_bank_details
        ->orderBy('id', 'desc')
        ->simplePaginate($this->itemsPerPage);

      return $this->respondInJSON(new CraydelJSONResponseType(
        true,
        LanguageTranslationHelper::translate('streams.success.listed'),
        (object)[
          'items' => collect($school_bank_details->items())->map(function ($school_bank_detail) {
            return [
              'id' => $school_bank_detail->id ?? null,
              'account_name' => $school_bank_detail->account_name ?? null,
              'account_number' => $school_bank_detail->account_number ?? null,
              'branch_name' => $school_bank_detail->branch_name ?? null,
              'bank_name' => $school_bank_detail->bank_name ?? null,
              'swift_code' => $school_bank_detail->swift_code ?? null,
              'school_id' => $school_bank_detail->school_id ?? null,
              'status' => $school_bank_detail->status ?? null,
              'created_at' => isset($school_bank_detail->created_at) && CraydelHelperFunctions::isDate($school_bank_detail->created_at) ?
                DateHelper::makeDisplayDateTime($school_bank_detail->created_at, 'd-m-Y') : null,
              'updated_at' => isset($school_bank_detail->updated_at) && CraydelHelperFunctions::isDate($school_bank_detail->updated_at) ?
                DateHelper::makeDisplayDateTime($school_bank_detail->updated_at, 'd-m-Y') : null,
            ];
          }),
          'items_per_page' => $this->itemsPerPage,
          'current_page' => $this->currentPaginationPage,
          'previous_page' => $this->previousPage(),
          'next_page' => $this->nextPage(),
          'number_of_pages' => $this->getTotalNumberOfPages(),
          'items_count' => $this->totalNumberOfEntities
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
