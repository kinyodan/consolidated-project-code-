<?php

namespace App\Http\Controllers\ManageSchoolAdmin\Queries;

use App\Http\Controllers\CraydelTypes\CraydelJSONResponseType;
use App\Http\Controllers\Helpers\CraydelHelperFunctions;
use App\Http\Controllers\Helpers\DateHelper;
use App\Http\Controllers\Helpers\GetLoggedIUserHelper;
use App\Http\Controllers\Helpers\LanguageTranslationHelper;
use App\Http\Controllers\Traits\CanCache;
use App\Http\Controllers\Traits\CanLog;
use App\Http\Controllers\Traits\CanPaginate;
use App\Http\Controllers\Traits\CanRespond;
use App\Models\SchoolAdmin;
use App\Models\SchoolStream;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;

class ListSchoolAdminQueryController
{
  use CanLog,CanCache,CanRespond, CanPaginate;
  
  /**
   * Handle the classes list quest
   *
   * @param Request $request
   * @param string $school_code
   * @return JsonResponse
   */
  public function handle(Request $request, string $school_code): JsonResponse
  {
    try{
      
      $school = GetLoggedIUserHelper::getUserSchool($request, $school_code);
      
      $admins = DB::table((new SchoolAdmin())->getTable())
        ->where('is_active', 1)
        ->where('school_id', $school->id);
      
      $currentPage = $request->input('page');
      
      $currentPage = !empty($currentPage) ? $currentPage : 1;
      $this->currentPaginationPage = $currentPage;
      $this->totalNumberOfEntities = $admins->count('id');
      $this->itemsPerPage = config('craydle.items_per_page', 10);
      
      Paginator::currentPageResolver(function () use ($currentPage) {
        return $currentPage;
      });
  
      $admins = $admins
        ->orderBy('id', 'desc')
        ->simplePaginate($this->itemsPerPage);
      
      return $this->respondInJSON(new CraydelJSONResponseType(
        true,
        LanguageTranslationHelper::translate('admins.success.listed'),
        (object)[
          'items' => collect($admins->items())->map(function ($admin){
            return [
              'id' => $admin->id ?? null,
              'school_id' => $admin->school_id ?? null,
              'admin_name' => $admin->admin_name ?? null,
              'admin_email' => $admin->admin_email ?? null,
              'admin_phone'=> $admin->admin_phone,
              'admin_role' => $admin->admin_role,
              'created_at' => isset($admin->created_at) && CraydelHelperFunctions::isDate($admin->created_at) ?
                DateHelper::makeDisplayDateTime($admin->created_at, 'd-m-Y') : null,
              'updated_at' => isset($admin->updated_at) && CraydelHelperFunctions::isDate($admin->updated_at) ?
                DateHelper::makeDisplayDateTime($admin->updated_at, 'd-m-Y') : null,
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
    }catch (Exception $exception){
      self::logException($exception);
      
      return $this->respondInJSON(new CraydelJSONResponseType(
        false,
        $exception->getMessage()
      ));
    }
  }
  
}