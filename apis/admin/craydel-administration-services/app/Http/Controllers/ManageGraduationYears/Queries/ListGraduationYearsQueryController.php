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


class ListGraduationYearsQueryController
{
  use CanCache, CanRespond, CanLog, CanPaginate;
  
  /**
   * Handle the classes list quest
   *
   * @param Request $request
   * @return JsonResponse
   */
  public function handle(Request $request): JsonResponse
  {
    try {
      $years = GraduationYear::where('is_active', '=', 1);
      $search = $request->input('search');
      if (!CraydelHelperFunctions::isNull($search)) {
        $years = $years->where('years', 'like', '%' . $search . '%');
      }
      $currentPage = $this->getCurrentPage($request);
      $this->currentPaginationPage = $currentPage;
      $this->totalNumberOfEntities = $years->count('id');
      $this->itemsPerPage = $request->input('items_per_page', config('craydle.items_per_page', 10));
      
      Paginator::currentPageResolver(function () use ($currentPage) {
        return $currentPage;
      });
      
      $sort_by = $request->input('sort_by');
      $sort_direction = $request->input('sort_direction');
      
      if (!CraydelHelperFunctions::isNull($sort_by) && !CraydelHelperFunctions::isNull($sort_direction)) {
        $years = $years->orderBy($sort_by, $sort_direction);
      } else {
        $years = $years->orderBy('id', 'desc');
      }
      
      $years = $years
        ->simplePaginate($this->itemsPerPage);
      
      return $this->respondInJSON(new CraydelJSONResponseType(
        true,
        LanguageTranslationHelper::translate('classes.success.listed'),
        (object)[
          'items' => collect($years->items())->map(function ($year) {
            return [
              'id' => $year->id ?? null,
              'years' => $year->year ?? null,
              'years' => $year->description ?? null,
              'created_at' => isset($year->created_at) && CraydelHelperFunctions::isDate($year->created_at) ?
                DateHelper::makeDisplayDateTime($year->created_at, 'd-m-Y') : null,
              'updated_at' => isset($year->updated_at) && CraydelHelperFunctions::isDate($year->updated_at) ?
                DateHelper::makeDisplayDateTime($year->updated_at, 'd-m-Y') : null,
              'created_by' => $year->created_by ?? null,
              'updated_by' => $year->updated_by ?? null,
              'deleted_by' => $year->deleted_by ?? null
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