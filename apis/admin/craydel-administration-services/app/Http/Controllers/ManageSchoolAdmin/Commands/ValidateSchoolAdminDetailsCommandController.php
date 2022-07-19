<?php

namespace App\Http\Controllers\ManageSchoolAdmin\Commands;

use App\Http\Controllers\Helpers\CraydelHelperFunctions;
use App\Http\Controllers\Helpers\CraydelInternalResponseHelper;
use App\Http\Controllers\Helpers\GetLoggedIUserHelper;
use App\Http\Controllers\Helpers\LanguageTranslationHelper;
use App\Http\Controllers\Traits\CanRespond;
use App\Models\SchoolAdmin;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Respect\Validation\Validator as v;

class ValidateSchoolAdminDetailsCommandController
{
  use CanRespond;

  public function handle(Request $request, string $school_code, bool $is_update = false): CraydelInternalResponseHelper
  {
    $user = GetLoggedIUserHelper::getUser($request);
    $school = GetLoggedIUserHelper::getUserSchool($request, $school_code);
    $admin_name = $request->input('admin_name');
    $admin_email = $request->input('admin_email');
    $admin_phone = $request->input('admin_phone');
    $admin_address = $request->input('admin_address');
    $admin_role = $request->input('admin_role');

    if (!v::stringVal()->notEmpty()->validate($admin_name)) {
      return (new CraydelInternalResponseHelper(
        false,
        LanguageTranslationHelper::translate('admins.errors.missing_name')
      ));
    }
    if (!v::stringVal()->notEmpty()->validate($admin_email)) {
      return (new CraydelInternalResponseHelper(
        false,
        LanguageTranslationHelper::translate('admins.errors.missing_email')
      ));
    }
    if (!CraydelHelperFunctions::isEmail($admin_email)) {
      return (new CraydelInternalResponseHelper(
        false,
        LanguageTranslationHelper::translate('admins.errors.incorrect_email')
      ));
    }
    if (!v::stringVal()->notEmpty()->validate($admin_phone)) {
      return (new CraydelInternalResponseHelper(
        false,
        LanguageTranslationHelper::translate('admins.errors.missing_phone')
      ));
    }
    if (!v::stringVal()->notEmpty()->validate($admin_address)) {
      return (new CraydelInternalResponseHelper(
        false,
        LanguageTranslationHelper::translate('admins.errors.missing_address')
      ));
    }
    if (!v::stringVal()->notEmpty()->validate($admin_role)) {
      return (new CraydelInternalResponseHelper(
        false,
        LanguageTranslationHelper::translate('admins.errors.missing_role')
      ));
    }


    $check_if_duplicate = DB::table((new SchoolAdmin())->getTable())
      ->where('school_id', $school->id)
      ->where('admin_email', $admin_email)
      ->exists();

    if ($check_if_duplicate && !$is_update) {
      return (new CraydelInternalResponseHelper(
        false,
        LanguageTranslationHelper::translate('admins.errors.admin_details_exists')
      ));
    }

    return $this->internalResponse(new CraydelInternalResponseHelper(
      true,
      'Validated', [
        'school_id' => $school->id ?? null,
        'admin_name' => CraydelHelperFunctions::toCleanString($admin_name),
        'admin_email' => CraydelHelperFunctions::toEmailAddress($admin_email),
        'admin_phone' => CraydelHelperFunctions::toCleanString($admin_phone),
        'admin_address' => CraydelHelperFunctions::toCleanString($admin_address),
        'admin_role' => CraydelHelperFunctions::toCleanString($admin_role),
        'is_active' => 1,
        'is_deleted' => 0,
        'created_at' => Carbon::now()->toDateTimeString(),
        'updated_at' => Carbon::now()->toDateTimeString(),
        'created_by' => $user->email ?? null,
        'updated_by' => $user->email ?? null
      ]
    ));
  }
}
