<?php

namespace App\Http\Controllers\ManageSchoolAdmin\Queries;

use App\Http\Controllers\CraydelTypes\CraydelJSONResponseType;
use App\Http\Controllers\Helpers\GetLoggedIUserHelper;
use App\Http\Controllers\Helpers\LanguageTranslationHelper;
use App\Http\Controllers\Traits\CanCache;
use App\Http\Controllers\Traits\CanLog;
use App\Http\Controllers\Traits\CanRespond;
use App\Models\SchoolAdmin;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ShowSchoolAdminQueryController
{
  use CanRespond,CanCache, CanLog;
  public function handle(Request $request, string $school_code, int $school_admin_id): JsonResponse
  {
    try{
      
      $school = GetLoggedIUserHelper::getUserSchool($request, $school_code);
      
      $admin = DB::table((new SchoolAdmin())->getTable())
        ->where('is_active', 1)
        ->where('id',$school_admin_id)
        ->where('school_id', $school->id)
        ->get();
      
      
      return $this->respondInJSON(new CraydelJSONResponseType(
        true,
        LanguageTranslationHelper::translate('admins.success.listed'),
        $admin
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