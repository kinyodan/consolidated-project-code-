<?php
namespace App\Http\Controllers\ManageSchoolBankDetails\Commands;

use App\Http\Controllers\CraydelTypes\CraydelJSONResponseType;
use App\Http\Controllers\Helpers\LanguageTranslationHelper;
use App\Http\Controllers\Traits\CanLog;
use App\Http\Controllers\Traits\CanRespond;
use App\Models\SchoolBankDetail;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UpdateSchoolBankDetailsCommandController
{
  use CanLog, CanRespond;
  
  /**
   * Add school class
   *
   * @param Request $request
   * @param string $school_code
   * @param $bank_detail_id
   * @return JsonResponse
   */
  public function handle(Request $request, string $school_code,$bank_detail_id): JsonResponse{
    try{
      $validation = (new ValidateSchoolBankDetailsCommandController())->handle($request, $school_code,true);
      
      if(!$validation->status){
        throw new Exception($validation->message ?? "Error while validating the stream details");
      }
      
      $saved = false;
      
      DB::transaction(function () use($validation,$bank_detail_id, &$saved){
        $saved = DB::table((new SchoolBankDetail())->getTable())
          ->where('id',$bank_detail_id)
          ->update($validation->data);
      });
      
      if(!$saved){
        throw new Exception("Error while saving the school bank details.");
      }
      
      return $this->respondInJSON(new CraydelJSONResponseType(
        true,
        LanguageTranslationHelper::translate('bank_details.success.updated')
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
