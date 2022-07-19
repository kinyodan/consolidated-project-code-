<?php

namespace App\Http\Controllers\ManageLicenses\Queries;

use App\Http\Controllers\CraydelTypes\CraydelJSONResponseType;
use App\Http\Controllers\Helpers\CraydelHelperFunctions;
use App\Http\Controllers\Helpers\DateHelper;
use App\Http\Controllers\Helpers\LanguageTranslationHelper;
use App\Http\Controllers\Traits\CanCache;
use App\Http\Controllers\Traits\CanLog;
use App\Http\Controllers\Traits\CanPaginate;
use App\Http\Controllers\Traits\CanRespond;
use App\Models\Student;
use Exception;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ListLicensesQueryController
{
  use CanRespond, CanLog, CanCache, CanPaginate;
  
  public function handle(Request $request): JsonResponse
  {
    try {
      
      $licenses = DB::table((new Student())->getTable())
        ->where('has_subscribed_for_assessment', '=', 1);
      
      $currentPage = $request->input('page');
      
      $currentPage = !empty($currentPage) ? $currentPage : 1;
      $this->currentPaginationPage = $currentPage;
      $this->totalNumberOfEntities = $licenses->count('id');
      $this->itemsPerPage = config('craydle.items_per_page', 10);
      
      Paginator::currentPageResolver(function () use ($currentPage) {
        return $currentPage;
      });
      
      $licenses = $licenses
        ->orderBy('id', 'desc')
        ->simplePaginate($this->itemsPerPage);
      
      return $this->respondInJSON(new CraydelJSONResponseType(
        true,
        LanguageTranslationHelper::translate('licenses.success.listed'),
        (object)[
          'items' => collect($licenses->items())->map(function ($license) {
            return [
              'id' => $license->id ?? null,
              'student_name' => $license->school_id ?? null,
              'student_email' => $license->admin_name ?? null,
              'school_name' => $license->school->school_name ?? null,
              'created_at' => isset($license->created_at) && CraydelHelperFunctions::isDate($license->created_at) ?
                DateHelper::makeDisplayDateTime($license->created_at, 'd-m-Y') : null,
              'updated_at' => isset($license->updated_at) && CraydelHelperFunctions::isDate($license->updated_at) ?
                DateHelper::makeDisplayDateTime($license->updated_at, 'd-m-Y') : null,
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