<?php
namespace App\Http\Controllers\ManageClasses\Commands;

use App\Http\Controllers\CraydelTypes\CraydelJSONResponseType;
use App\Http\Controllers\Helpers\CraydelHelperFunctions;
use App\Http\Controllers\Helpers\GetLoggedIUserHelper;
use App\Http\Controllers\Helpers\LanguageTranslationHelper;
use App\Http\Controllers\Traits\CanLog;
use App\Http\Controllers\Traits\CanRespond;
use App\Models\School;
use App\Models\Student;
use App\Models\UniversalSchoolClass;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DeleteClassCommandController
{
  use CanLog, CanRespond;
  
  /**
   * Add school class
   *
   * @param Request $request
   * @param int $class_id
   * @return JsonResponse
   */
  public function handle(Request $request, int $class_id): JsonResponse{
    try{
      $user = GetLoggedIUserHelper::getUser($request);
      
      $saved = false;
      
      DB::transaction(function () use($class_id, $user, &$saved){
        $current_class = DB::table((new UniversalSchoolClass())->getTable())
          ->where('id', $class_id)
          ->first();
  
        $class_has_students = DB::table((new Student())->getTable())
          ->where('class_id', CraydelHelperFunctions::toNumbers($class_id))
          ->exists();
  
        if ($class_has_students) {
          throw new Exception("You can not delete a class with linked students.");
        }
        
        $saved = DB::table((new UniversalSchoolClass())->getTable())
          ->where('id', $class_id)
          ->update([
            'class_name' => CraydelHelperFunctions::createSoftDeleteValue($current_class->class_name),
            'class_name_slug' => CraydelHelperFunctions::createSoftDeleteValue($current_class->class_name_slug),
            'is_deleted' => 1,
            'deleted_by' => $user->email ?? null,
            'deleted_at' => Carbon::now()->toDateTimeString(),
            'is_active' => 0
          ]);
      });
      
      if(!$saved){
        throw new Exception("Error while deleting the class name details.");
      }
      
      return $this->respondInJSON(new CraydelJSONResponseType(
        true,
        LanguageTranslationHelper::translate('classes.success.class_deleted')
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
