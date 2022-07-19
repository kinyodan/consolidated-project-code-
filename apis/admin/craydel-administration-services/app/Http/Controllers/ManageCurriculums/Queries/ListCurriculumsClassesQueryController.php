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
use App\Models\UniversalSchoolClass;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;

class ListCurriculumsClassesQueryController
{
  
  use CanLog,CanRespond,CanCache,CanPaginate;
  
  public function handle(Request $request, $curriculums_id): JsonResponse
  {
    try {
      $classes = UniversalSchoolClass::where('curriculum_id', '=', $curriculums_id);
     
      $currentPage = $this->getCurrentPage($request);
      $this->currentPaginationPage = $currentPage;
      $this->totalNumberOfEntities = $classes->count('id');
      $this->itemsPerPage = $request->input('items_per_page', config('craydle.items_per_page', 10));
      
      Paginator::currentPageResolver(function () use ($currentPage) {
        return $currentPage;
      });
      
      $sort_by = $request->input('sort_by');
      $sort_direction = $request->input('sort_direction');
      
      if (!CraydelHelperFunctions::isNull($sort_by) && !CraydelHelperFunctions::isNull($sort_direction)) {
        $classes = $classes->orderBy($sort_by, $sort_direction);
      } else {
        $classes = $classes->orderBy('id', 'desc');
      }
  
      $classes = $classes
        ->simplePaginate($this->itemsPerPage);
      
      return $this->respondInJSON(new CraydelJSONResponseType(
        true,
        LanguageTranslationHelper::translate('schools.success.listed'),
        (object)[
          'items' => collect($classes->items())->map(function ($classes) {
            return [
              'id' => $classes->id ?? null,
              'class_name' =>$classes->class_name,
              'created_at' => isset($classes->created_at) && CraydelHelperFunctions::isDate($classes->created_at) ?
                DateHelper::makeDisplayDateTime($classes->created_at, 'd-m-Y') : null,
              'updated_at' => isset($classes->updated_at) && CraydelHelperFunctions::isDate($classes->updated_at) ?
                DateHelper::makeDisplayDateTime($classes->updated_at, 'd-m-Y') : null,
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