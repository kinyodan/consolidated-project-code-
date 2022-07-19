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


class ListManageCurriculumsQueryController
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
      $curriculums = Curriculum::where('is_active', '=', 1);
      $search = $request->input('search');
      if(!CraydelHelperFunctions::isNull($search)){
        $curriculums = $curriculums->where('curriculum_name', 'like', '%'.$search.'%');
      }
      $currentPage = $this->getCurrentPage($request);
      $this->currentPaginationPage = $currentPage;
      $this->totalNumberOfEntities = $curriculums->count('id');
      $this->itemsPerPage = $request->input('items_per_page', config('craydle.items_per_page', 10));
  
      Paginator::currentPageResolver(function () use ($currentPage) {
        return $currentPage;
      });
  
      $sort_by = $request->input('sort_by');
      $sort_direction = $request->input('sort_direction');
  
      if(!CraydelHelperFunctions::isNull($sort_by) && !CraydelHelperFunctions::isNull($sort_direction)){
        $curriculums = $curriculums->orderBy($sort_by, $sort_direction);
      }else{
        $curriculums = $curriculums->orderBy('id', 'desc');
      }
  
      $curriculums = $curriculums
        ->simplePaginate($this->itemsPerPage);
      
  
      return $this->respondInJSON(new CraydelJSONResponseType(
        true,
        LanguageTranslationHelper::translate('curriculum.success.listed'),
        (object)[
          'items' => collect($curriculums->items())->map(function ($curriculums){
            return [
              'id' => $curriculums->id ?? null,
              'country_code' =>$curriculums->country_code,
              'country' =>$curriculums->country->name ?? null,
              'curriculum_name' => $curriculums->curriculum_name ?? null,
              'curriculum_code'=> $curriculums->curriculum_code,
              'created_at' => isset($curriculums->created_at) && CraydelHelperFunctions::isDate($curriculums->created_at) ?
                DateHelper::makeDisplayDateTime($curriculums->created_at, 'd-m-Y') : null,
              'updated_at' => isset($curriculums->updated_at) && CraydelHelperFunctions::isDate($curriculums->updated_at) ?
                DateHelper::makeDisplayDateTime($curriculums->updated_at, 'd-m-Y') : null,
              'created_by' => $curriculums->created_by ?? null,
              'updated_by' => $curriculums->updated_by ?? null,
              'deleted_by' => $curriculums->deleted_by ?? null
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
