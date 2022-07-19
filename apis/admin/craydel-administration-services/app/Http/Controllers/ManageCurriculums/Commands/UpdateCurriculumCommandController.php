<?php
namespace App\Http\Controllers\ManageCurriculums\Commands;

use App\Http\Controllers\CraydelTypes\CraydelJSONResponseType;
use App\Http\Controllers\Helpers\LanguageTranslationHelper;
use App\Http\Controllers\Traits\CanLog;
use App\Http\Controllers\Traits\CanRespond;
use App\Models\Curriculum;
use App\Models\UniversalSchoolClass;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UpdateCurriculumCommandController
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
      $validation = (new ValidateCurriculumController())->handle($request, true);
      
      if(!$validation->status){
        throw new Exception($validation->message ?? "Error while validating the class details");
      }
      
      $saved = false;
      
      DB::transaction(function () use($validation, $curriculums_id, &$saved){
        $saved = DB::table((new Curriculum())->getTable())
          ->where('id', $curriculums_id)
          ->update($validation->data);
      });
      
      if(!$saved){
        throw new Exception("Error while updating the class name details.");
      }
      
      return $this->respondInJSON(new CraydelJSONResponseType(
        true,
        LanguageTranslationHelper::translate('curriculum.success.updated')
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
