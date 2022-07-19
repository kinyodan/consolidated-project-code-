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
use App\Models\School;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;

class ListSchoolsCurriculumController
{
  use CanPaginate,CanCache,CanRespond,CanLog;
  
  public function handle(Request $request, $school_id): JsonResponse
  {
    try {
      $schools = School::where('id', '=', $school_id);
      $search = $request->input('search');
      if (!CraydelHelperFunctions::isNull($search)) {
        $schools = $schools->where('school_name', 'like', '%' . $search . '%');
      }
      $currentPage = $this->getCurrentPage($request);
      $this->currentPaginationPage = $currentPage;
      $this->totalNumberOfEntities = $schools->count('id');
      $this->itemsPerPage = $request->input('items_per_page', config('craydle.items_per_page', 10));
      
      Paginator::currentPageResolver(function () use ($currentPage) {
        return $currentPage;
      });
      
      $sort_by = $request->input('sort_by');
      $sort_direction = $request->input('sort_direction');
      
      if (!CraydelHelperFunctions::isNull($sort_by) && !CraydelHelperFunctions::isNull($sort_direction)) {
        $schools = $schools->orderBy($sort_by, $sort_direction);
      } else {
        $schools = $schools->orderBy('id', 'desc');
      }
      
      $schools = $schools
        ->simplePaginate($this->itemsPerPage);
      
      return $this->respondInJSON(new CraydelJSONResponseType(
        true,
        LanguageTranslationHelper::translate('schools.success.listed'),
        (object)[
          'items' => collect($schools->items())->map(function ($school) {
            return [
              'id' => $school->id ?? null,
              'school_name' => $school->school_name,
              'curriculums' =>$school->curriculum_id,
              'curriculum_name' =>$school->curriculum->curriculum_name,
              'country' =>  $school->country->name ?? null,
              'created_at' => isset($school->created_at) && CraydelHelperFunctions::isDate($school->created_at) ?
                DateHelper::makeDisplayDateTime($school->created_at, 'd-m-Y') : null,
              'updated_at' => isset($school->updated_at) && CraydelHelperFunctions::isDate($school->updated_at) ?
                DateHelper::makeDisplayDateTime($school->updated_at, 'd-m-Y') : null,
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