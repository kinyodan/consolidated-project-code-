<?php
namespace App\Http\Controllers\ManageCurriculums\Commands;

use App\Http\Controllers\CraydelTypes\CraydelJSONResponseType;
use App\Http\Controllers\Helpers\CraydelHelperFunctions;
use App\Http\Controllers\Helpers\GetLoggedIUserHelper;
use App\Http\Controllers\Helpers\LanguageTranslationHelper;
use App\Http\Controllers\Traits\CanLog;
use App\Http\Controllers\Traits\CanRespond;
use App\Models\Curriculum;
use App\Models\School;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DeleteCurriculumCommandController
{
  use CanLog, CanRespond;
  
  /**
   * Add school class
   *
   * @param Request $request
   * @param int $curriculums_id
   * @return JsonResponse
   */
  public function handle(Request $request, int $curriculums_id): JsonResponse{
    try{
      $user = GetLoggedIUserHelper::getUser($request);
      
      $saved = false;
      
      DB::transaction(function () use($curriculums_id, $user, &$saved){
        $current_curriculum = DB::table((new Curriculum())->getTable())
          ->where('id', $curriculums_id)
          ->first();
  
        $check_if_curriculum_used = DB::table((new School())->getTable())
          ->where('curriculum_id', CraydelHelperFunctions::slugifyString($current_curriculum->id))
          ->where('country_code', CraydelHelperFunctions::slugifyString($current_curriculum->country_code))
          ->exists();
        
        
        if($check_if_curriculum_used) {
          throw new Exception("You can not delete curriculum with linked schools.");
        }
          $saved = DB::table((new Curriculum())->getTable())
            ->where('id', $curriculums_id)
            ->update([
              'curriculum_name' => CraydelHelperFunctions::createSoftDeleteValue($current_curriculum->curriculum_name),
              'curriculum_slug' => CraydelHelperFunctions::createSoftDeleteValue($current_curriculum->curriculum_slug),
              'is_deleted' => 1,
              'deleted_by' => $user->email ?? null,
              'deleted_at' => Carbon::now()->toDateTimeString(),
              'is_active' => 0
            ]);
       
      });
      
      if(!$saved){
        throw new Exception("Error while deleting the curriculum  details.");
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
