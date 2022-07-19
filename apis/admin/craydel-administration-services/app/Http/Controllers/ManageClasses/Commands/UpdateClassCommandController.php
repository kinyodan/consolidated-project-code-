<?php
namespace App\Http\Controllers\ManageClasses\Commands;

use App\Http\Controllers\CraydelTypes\CraydelJSONResponseType;
use App\Http\Controllers\Helpers\LanguageTranslationHelper;
use App\Http\Controllers\Traits\CanLog;
use App\Http\Controllers\Traits\CanRespond;
use App\Models\UniversalSchoolClass;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UpdateClassCommandController
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
      $validation = (new ValidateClassController())->handle($request, true);
      
      if(!$validation->status){
        throw new Exception($validation->message ?? "Error while validating the class details");
      }
      
      $saved = false;
      
      DB::transaction(function () use($validation, $class_id, &$saved){
        $saved = DB::table((new UniversalSchoolClass())->getTable())
          ->where('id', $class_id)
          ->update($validation->data);
      });
      
      if(!$saved){
        throw new Exception("Error while updating the class name details.");
      }
      
      return $this->respondInJSON(new CraydelJSONResponseType(
        true,
        LanguageTranslationHelper::translate('classes.success.class_updated')
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
