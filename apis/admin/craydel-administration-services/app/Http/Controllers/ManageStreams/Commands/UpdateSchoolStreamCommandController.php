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

class UpdateSchoolStreamCommandController
{
  use CanLog, CanRespond;
  
  /**
   * Add school class
   *
   * @param Request $request
   * @param string $school_code
   * @param int $stream_id
   * @return JsonResponse
   */
  public function handle(Request $request, string $school_code, int $stream_id): JsonResponse{
    try{
      $validation = (new ValidateSchoolStreamController())->handle($request, $school_code, true);
      
      if(!$validation->status){
        throw new Exception($validation->message ?? "Error while validating the stream details");
      }
      
      $saved = false;
      
      DB::transaction(function () use($validation, $stream_id, &$saved){
        $saved = DB::table((new SchoolStream())->getTable())
          ->where('id', $stream_id)
          ->update($validation->data);
      });
      
      if(!$saved){
        throw new Exception("Error while updating the stream name details.");
      }
      
      return $this->respondInJSON(new CraydelJSONResponseType(
        true,
        LanguageTranslationHelper::translate('streams.success.updated')
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
