<?php
namespace App\Http\Controllers\ManageStreams\Commands;

use App\Http\Controllers\CraydelTypes\CraydelJSONResponseType;
use App\Http\Controllers\Helpers\LanguageTranslationHelper;
use App\Http\Controllers\Traits\CanLog;
use App\Http\Controllers\Traits\CanRespond;
use App\Models\SchoolStream;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AddSchoolStreamCommandController
{
  use CanLog, CanRespond;
  
  /**
   * Add school class
   *
   * @param Request $request
   * @param string $school_code
   * @return JsonResponse
   */
  public function handle(Request $request, string $school_code): JsonResponse{
    try{
      $validation = (new ValidateSchoolStreamController())->handle($request, $school_code);
      
      if(!$validation->status){
        throw new Exception($validation->message ?? "Error while validating the stream details");
      }
      
      $saved = false;
      
      DB::transaction(function () use($validation, &$saved){
        $saved = DB::table((new SchoolStream())->getTable())
          ->insert($validation->data);
      });
      
      if(!$saved){
        throw new Exception("Error while saving the strean details.");
      }
      
      return $this->respondInJSON(new CraydelJSONResponseType(
        true,
        LanguageTranslationHelper::translate('streams.success.added')
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
