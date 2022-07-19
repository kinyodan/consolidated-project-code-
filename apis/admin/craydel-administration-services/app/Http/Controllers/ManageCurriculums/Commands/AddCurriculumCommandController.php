<?php
namespace App\Http\Controllers\ManageCurriculums\Commands;

use App\Http\Controllers\CraydelTypes\CraydelJSONResponseType;
use App\Http\Controllers\Helpers\LanguageTranslationHelper;
use App\Http\Controllers\Traits\CanLog;
use App\Http\Controllers\Traits\CanRespond;
use App\Models\Curriculum;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AddCurriculumCommandController
{
  use CanLog, CanRespond;
  
  /**
   * Add school class
   *
   * @param Request $request
   * @return JsonResponse
   */
  public function handle(Request $request): JsonResponse{
    try{
      
      $validation = (new ValidateCurriculumController())->handle($request);
      if(!$validation->status){
        throw new Exception($validation->message ?? "Error while validating the class details");
      }
      
      $saved = false;
      
      DB::transaction(function () use($validation, &$saved){
        $saved = DB::table((new Curriculum())->getTable())
          ->insert($validation->data);
      });
      
      if(!$saved){
        throw new Exception("Error while saving the class name details.");
      }
      
      return $this->respondInJSON(new CraydelJSONResponseType(
        true,
        LanguageTranslationHelper::translate('curriculum.success.added')
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
