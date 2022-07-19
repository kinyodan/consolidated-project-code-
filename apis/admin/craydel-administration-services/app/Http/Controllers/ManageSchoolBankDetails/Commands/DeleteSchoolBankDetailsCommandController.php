<?php
namespace App\Http\Controllers\ManageSchoolBankDetails\Commands;

use App\Http\Controllers\CraydelTypes\CraydelJSONResponseType;
use App\Http\Controllers\Helpers\CraydelHelperFunctions;
use App\Http\Controllers\Helpers\GetLoggedIUserHelper;
use App\Http\Controllers\Helpers\LanguageTranslationHelper;
use App\Http\Controllers\Traits\CanLog;
use App\Http\Controllers\Traits\CanRespond;
use App\Models\SchoolBankDetail;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DeleteSchoolBankDetailsCommandController
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
      $user = GetLoggedIUserHelper::getUser($request);
      $school = GetLoggedIUserHelper::getUserSchool($request, $school_code);
      $saved = false;
      
      DB::transaction(function () use($bank_detail_id, $user,$school, &$saved){
        $current_school_bank_details = DB::table((new SchoolBankDetail())->getTable())
          ->where('id', $bank_detail_id)
          ->where('school_id',$school->id)
          ->first();
        
        $saved = DB::table((new SchoolBankDetail())->getTable())
          ->where('id', $bank_detail_id)
          ->update([
            'account_name' => CraydelHelperFunctions::createSoftDeleteValue($current_school_bank_details->account_name),
            'account_number' => CraydelHelperFunctions::createSoftDeleteValue($current_school_bank_details->account_number),
            'swift_code' => CraydelHelperFunctions::createSoftDeleteValue($current_school_bank_details->swift_code),
            'is_deleted' => 1,
            'deleted_by' => $user->email ?? null,
            'deleted_at' => Carbon::now()->toDateTimeString(),
            'status' => 0
          ]);
      });
      
      if(!$saved){
        throw new Exception("Error while deleting the class name details.");
      }
      
      return $this->respondInJSON(new CraydelJSONResponseType(
        true,
        LanguageTranslationHelper::translate('bank_details.success.deleted')
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
