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
use Illuminate\Pagination\Paginator;


class ListClassesQueryController
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
      $classes = UniversalSchoolClass::where('universal_school_classes.is_active', '=', 1)
        ->join('curriculums', 'curriculums.id', '=', 'universal_school_classes.curriculum_id')
        ->select('universal_school_classes.*', 'curriculums.country_code as country_code','curriculums.curriculum_code');
      $search = $request->input('search');
      if(!CraydelHelperFunctions::isNull($search)){
        $classes = $classes->where('class_name', 'like', '%'.$search.'%');
      }
      $currentPage = $this->getCurrentPage($request);
      $this->currentPaginationPage = $currentPage;
      $this->totalNumberOfEntities = $classes->count('universal_school_classes.id');
      $this->itemsPerPage = $request->input('items_per_page', config('craydle.items_per_page', 10));
  
      Paginator::currentPageResolver(function () use ($currentPage) {
        return $currentPage;
      });
  
      $sort_by = $request->input('sort_by');
      $sort_direction = $request->input('sort_direction');
  
      if(!CraydelHelperFunctions::isNull($sort_by) && !CraydelHelperFunctions::isNull($sort_direction)){
        $classes = $classes->orderBy($sort_by, $sort_direction);
      }else{
        $classes = $classes->orderBy('universal_school_classes.id', 'desc');
      }
  
      $classes = $classes
        ->simplePaginate($this->itemsPerPage);
  
      return $this->respondInJSON(new CraydelJSONResponseType(
        true,
        LanguageTranslationHelper::translate('classes.success.listed'),
        (object)[
          'items' => collect($classes->items())->map(function ($class){
            return [
              'id' => $class->id ?? null,
              'status' => $class->is_active ?? null,
              'class_name' => $class->class_name ?? null,
              'curriculum_code'=> $class->curriculum_code,
              'created_at' => isset($class->created_at) && CraydelHelperFunctions::isDate($class->created_at) ?
                DateHelper::makeDisplayDateTime($class->created_at, 'd-m-Y') : null,
              'updated_at' => isset($class->updated_at) && CraydelHelperFunctions::isDate($class->updated_at) ?
                DateHelper::makeDisplayDateTime($class->updated_at, 'd-m-Y') : null,
              'created_by' => $class->created_by ?? null,
              'updated_by' => $class->updated_by ?? null,
              'deleted_by' => $class->deleted_by ?? null
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
