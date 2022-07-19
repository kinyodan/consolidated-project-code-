<?php

namespace App\Http\Controllers\ManageSchoolBankDetails\Commands;

use App\Http\Controllers\Helpers\CraydelHelperFunctions;
use App\Http\Controllers\Helpers\CraydelInternalResponseHelper;
use App\Http\Controllers\Helpers\GetLoggedIUserHelper;
use App\Http\Controllers\Helpers\LanguageTranslationHelper;
use App\Http\Controllers\Traits\CanRespond;
use App\Models\SchoolBankDetail;
use App\Models\SchoolStream;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Respect\Validation\Validator as v;

class ValidateSchoolBankDetailsCommandController
{
  use CanRespond;
  
  /**
   * Validate
   *
   * @param Request $request
   * @param string $school_code
   * @param bool $is_update
   * @return CraydelInternalResponseHelper
   */
  public function handle(Request $request, string $school_code, bool $is_update = false): CraydelInternalResponseHelper
  {
    $user = GetLoggedIUserHelper::getUser($request);
    $school = GetLoggedIUserHelper::getUserSchool($request, $school_code);
    $account_name = $request->input('account_name');
    $account_number = $request->input('account_number');
    $bank_name = $request->input('bank_name');
    $branch_name = $request->input('branch_name');
    $swift_code = $request->input('swift_code');
    
    if (!v::stringVal()->notEmpty()->validate($account_name)) {
      return (new CraydelInternalResponseHelper(
        false,
        LanguageTranslationHelper::translate('bank_details.errors.missing_account_name')
      ));
    }
    if (!v::stringVal()->notEmpty()->validate($account_number)) {
      return (new CraydelInternalResponseHelper(
        false,
        LanguageTranslationHelper::translate('bank_details.errors.missing_account_number')
      ));
    }
    if (!v::stringVal()->notEmpty()->validate($bank_name)) {
      return (new CraydelInternalResponseHelper(
        false,
        LanguageTranslationHelper::translate('bank_details.errors.missing_bank_name')
      ));
    }
    if (!v::stringVal()->notEmpty()->validate($branch_name)) {
      return (new CraydelInternalResponseHelper(
        false,
        LanguageTranslationHelper::translate('bank_details.errors.missing_branch_name')
      ));
    }
    if (!v::stringVal()->notEmpty()->validate($swift_code)) {
      return (new CraydelInternalResponseHelper(
        false,
        LanguageTranslationHelper::translate('bank_details.errors.missing_swift_code')
      ));
    }
    
    
    $check_if_duplicate = DB::table((new SchoolBankDetail())->getTable())
      ->where('school_id', $school->id)
      ->where('account_number', $account_number)
      ->where('swift_code', $swift_code)
      ->exists();
    
    if ($check_if_duplicate && !$is_update) {
      return (new CraydelInternalResponseHelper(
        false,
        LanguageTranslationHelper::translate('bank_details.errors.bank_details_name_exists')
      ));
    }
    
    return $this->internalResponse(new CraydelInternalResponseHelper(
      true,
      'Validated', [
        'account_name' => CraydelHelperFunctions::toCleanString($account_name),
        'account_number' => CraydelHelperFunctions::toCleanString($account_number),
        'bank_name' => CraydelHelperFunctions::toCleanString($bank_name),
        'branch_name' => CraydelHelperFunctions::toCleanString($branch_name),
        'swift_code' => CraydelHelperFunctions::toCleanString($swift_code),
        'school_id' => $school->id ?? null,
        'status' => 1,
        'created_at' => Carbon::now()->toDateTimeString(),
        'updated_at' => Carbon::now()->toDateTimeString(),
        'created_by' => $user->email ?? null,
        'updated_by' => $user->email ?? null
      ]
    ));
  }
}
