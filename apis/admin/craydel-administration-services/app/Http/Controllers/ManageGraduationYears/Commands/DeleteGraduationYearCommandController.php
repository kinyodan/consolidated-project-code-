<?php
namespace App\Http\Controllers\ManageGraduationYears\Commands;

use App\Http\Controllers\CraydelTypes\CraydelJSONResponseType;
use App\Http\Controllers\Helpers\CraydelHelperFunctions;
use App\Http\Controllers\Helpers\GetLoggedIUserHelper;
use App\Http\Controllers\Helpers\LanguageTranslationHelper;
use App\Http\Controllers\Traits\CanLog;
use App\Http\Controllers\Traits\CanRespond;
use App\Models\GraduationYear;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DeleteGraduationYearCommandController
{
  use CanLog, CanRespond;
  
  /**
   * Add school class
   *
   * @param Request $request
   * @param int $year_id
   * @return JsonResponse
   */
  public function handle(Request $request, int $year_id): JsonResponse{
    try{
      $user = GetLoggedIUserHelper::getUser($request);
      
      $saved = false;
      DB::transaction(function () use($year_id, $user, &$saved){
        $current_class = DB::table((new GraduationYear())->getTable())
          ->where('id', $year_id)
          ->first();
        
        $saved = DB::table((new GraduationYear())->getTable())
          ->where('id', $year_id)
          ->update([
            'year' => CraydelHelperFunctions::createSoftDeleteValue($current_class->year),
            'description' => CraydelHelperFunctions::createSoftDeleteValue($current_class->description),
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
        LanguageTranslationHelper::translate('curriculum.success.deleted')
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
