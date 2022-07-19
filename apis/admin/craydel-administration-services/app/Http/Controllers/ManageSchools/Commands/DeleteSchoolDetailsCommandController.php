<?php

namespace App\Http\Controllers\ManageSchools\Commands;

use App\Http\Controllers\CraydelTypes\CraydelJSONResponseType;
use App\Http\Controllers\Helpers\CraydelHelperFunctions;
use App\Http\Controllers\Helpers\GetLoggedIUserHelper;
use App\Http\Controllers\Helpers\LanguageTranslationHelper;
use App\Http\Controllers\Traits\CanLog;
use App\Http\Controllers\Traits\CanRespond;
use App\Models\School;
use App\Models\Student;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DeleteSchoolDetailsCommandController
{
  use CanLog, CanRespond;
  
  /**
   * Add school class
   *
   * @param Request $request
   * @param int $school_id
   * @return JsonResponse
   */
  public function handle(Request $request, int $school_id): JsonResponse
  {
    try {
    
      $user = GetLoggedIUserHelper::getUser($request);
      
      $saved = false;
      
      DB::transaction(function () use ($school_id, $user, &$saved) {
        $current_school = DB::table((new School())->getTable())
          ->where('id', $school_id)
          ->first();
  
        $check_if_school_used = DB::table((new Student())->getTable())
          ->where('school_id', CraydelHelperFunctions::slugifyString($current_school->id))
          ->exists();
        if($check_if_school_used) {
          throw new Exception("You can not delete school with linked students.");
        }
          $saved = DB::table((new School())->getTable())
            ->where('id', $current_school->id)
            ->update([
              'school_name' => CraydelHelperFunctions::createSoftDeleteValue($current_school->school_name),
              'school_email' => CraydelHelperFunctions::createSoftDeleteValue($current_school->school_email),
              'status' => 0,
              'is_deleted' => 1,
              'deleted_by' => $user->email ?? null,
              'deleted_at' => Carbon::now()->toDateTimeString(),
            ]);
        
      });
      
      if (!$saved) {
        throw new Exception("Error while deleting the school details.");
      }
      
      return $this->respondInJSON(new CraydelJSONResponseType(
        true,
        LanguageTranslationHelper::translate('schools.success.deleted')
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
