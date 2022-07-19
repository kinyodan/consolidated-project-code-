<?php

namespace App\Http\Controllers\ManageSchoolAdmin\Commands;

use App\Http\Controllers\CraydelTypes\CraydelJSONResponseType;
use App\Http\Controllers\Helpers\CraydelHelperFunctions;
use App\Http\Controllers\Helpers\GetLoggedIUserHelper;
use App\Http\Controllers\Helpers\LanguageTranslationHelper;
use App\Http\Controllers\Traits\CanRespond;
use App\Models\SchoolAdmin;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DeleteSchoolAdminDetailsCommandController
{
  use CanRespond;
  public function handle(Request $request, string $school_code,$school_admin_id): JsonResponse
  {
    try {
      $user = GetLoggedIUserHelper::getUser($request);
      $school = GetLoggedIUserHelper::getUserSchool($request, $school_code);
      $saved = false;
    
      DB::transaction(function () use ($school_admin_id, $user, $school, &$saved) {
        $current_school_admin_details = DB::table((new SchoolAdmin())->getTable())
          ->where('id', $school_admin_id)
          ->where('school_id', $school->id)
          ->first();
      
        $saved = DB::table((new SchoolAdmin())->getTable())
          ->where('id', $school_admin_id)
          ->update([
            'admin_name' => CraydelHelperFunctions::createSoftDeleteValue($current_school_admin_details->admin_name),
            'admin_email' => CraydelHelperFunctions::createSoftDeleteValue($current_school_admin_details->admin_email),
            'admin_phone' => CraydelHelperFunctions::createSoftDeleteValue($current_school_admin_details->admin_phone),
            'is_deleted' => 1,
            'deleted_by' => $user->email ?? null,
            'deleted_at' => Carbon::now()->toDateTimeString(),
            'is_active' => 0
          ]);
      });
    
      if (!$saved) {
        throw new Exception("Error while deleting the class name details.");
      }
    
      return $this->respondInJSON(new CraydelJSONResponseType(
        true,
        LanguageTranslationHelper::translate('admins.success.deleted')
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