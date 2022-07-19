<?php

namespace App\Http\Controllers\ManageSchools\Commands;

use App\Http\Controllers\Helpers\CountryHelper;
use App\Http\Controllers\Helpers\CraydelHelperFunctions;
use App\Http\Controllers\Helpers\CraydelInternalResponseHelper;
use App\Http\Controllers\Helpers\GetLoggedIUserHelper;
use App\Http\Controllers\Helpers\LanguageTranslationHelper;
use App\Http\Controllers\ManageSchools\ManageSchoolsController;
use App\Http\Controllers\Traits\CanRespond;
use App\Http\Controllers\Traits\CanUploadImage;
use App\Models\School;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use \Respect\Validation\Validator as v;


class ValidateSchoolDetailsCommandController
{
  use CanRespond, CanUploadImage;

  /**
   * Validate
   *
   * @param Request $request
   * @param bool $is_update
   * @return CraydelInternalResponseHelper
   * @throws Exception
   */
  public function handle(Request $request, bool $is_update = false): CraydelInternalResponseHelper
  {

    $user = GetLoggedIUserHelper::getUser($request);
    $curriculum_id = $request->input('curriculum_id');
    $country_code = $request->input('country_code');
    $discount_value = $request->input('discount_value');
    $school_name = $request->input('school_name');
    $school_email = $request->input('school_email');
    $school_phone = $request->input('school_phone');
    $school_address = $request->input('school_address');
    $school_physical_address = $request->input('school_physical_address');
    $school_website_url = $request->input('school_website_url');
    $school_logo = $request->input('school_logo');
    $school_inverse_logo= $request->input('school_inverse_logo');
    $parent_school_id = $request->input('parent_school_id');
    $_validate_school_logo = null;
    $_validate_school_inverse_logo = null;

    $school_code = CraydelHelperFunctions::makeRandomNumber(10);

    if (!v::stringVal()->notEmpty()->validate($school_name)) {
      return (new CraydelInternalResponseHelper(
        false,
        LanguageTranslationHelper::translate('schools.errors.missing_name')
      ));
    }
    if (!v::stringVal()->notEmpty()->validate($school_email)) {
      return (new CraydelInternalResponseHelper(
        false,
        LanguageTranslationHelper::translate('schools.errors.missing_school_email')
      ));
    }
    if (!CraydelHelperFunctions::isEmail($school_email)) {
      return (new CraydelInternalResponseHelper(
        false,
        LanguageTranslationHelper::translate('schools.errors.incorrect_email')
      ));
    }

    if (!v::stringVal()->notEmpty()->validate($school_phone)) {
      return (new CraydelInternalResponseHelper(
        false,
        LanguageTranslationHelper::translate('schools.errors.school_phone')
      ));
    }
    if (!v::stringVal()->notEmpty()->validate($school_phone)) {
      return (new CraydelInternalResponseHelper(
        false,
        LanguageTranslationHelper::translate('schools.errors.school_phone')
      ));
    }
    if (!v::stringVal()->notEmpty()->validate($school_address)) {
      return (new CraydelInternalResponseHelper(
        false,
        LanguageTranslationHelper::translate('schools.errors.school_address')
      ));
    }

    $check_if_duplicate = DB::table((new School())->getTable())
      ->where('school_name', $school_name)
      ->exists();
    $check_if_school_email_exists = DB::table((new School())->getTable())
      ->where('school_email', $school_email)
      ->exists();
    $check_if_phone_number_exist = DB::table((new School())->getTable())
      ->where('school_phone', $school_phone)
      ->exists();
    if ($check_if_duplicate && !$is_update) {
      return (new CraydelInternalResponseHelper(
        false,
        LanguageTranslationHelper::translate('schools.errors.name_exists')
      ));
    }
    if ($check_if_school_email_exists && !$is_update) {
      return (new CraydelInternalResponseHelper(
        false,
        LanguageTranslationHelper::translate('schools.errors.email_exists')
      ));
    }
    if ($check_if_phone_number_exist && !$is_update) {
      return (new CraydelInternalResponseHelper(
        false,
        LanguageTranslationHelper::translate('schools.errors.phone_number_exists')
      ));
    }
    if(is_string($school_logo) && !CraydelHelperFunctions::isNull($school_logo)){
      if(!CraydelHelperFunctions::isURL($school_logo)){
        $_validate_school_logo = self::validateBase64Image(
          $school_logo,
          ManageSchoolsController::$allowed_image_extensions
        );

        if($_validate_school_logo->status === false){
          throw new Exception("Failed to upload student image with error : ".$_validate_school_logo->message);
        }
      }
    }
    if(is_string($school_inverse_logo) && !CraydelHelperFunctions::isNull($school_inverse_logo)){
      if(!CraydelHelperFunctions::isURL($school_inverse_logo)){
        $_validate_school_inverse_logo = self::validateBase64Image(
          $school_inverse_logo,
          ManageSchoolsController::$allowed_image_extensions
        );

        if($_validate_school_inverse_logo->status === false){
          throw new Exception("Failed to upload student image with error : ".$_validate_school_inverse_logo->message);
        }
      }
    }



    return $this->internalResponse(new CraydelInternalResponseHelper(
      true,
      'Validated', [
        'curriculum_id' => CraydelHelperFunctions::toCleanString($curriculum_id),
        'school_code' => CraydelHelperFunctions::toCleanString($school_code),
        'country_id' => CountryHelper::getCountryId($country_code),
        'country_code' => CraydelHelperFunctions::toCleanString($country_code),
        'discount_value' => CraydelHelperFunctions::toCleanString($discount_value),
        'school_name' => CraydelHelperFunctions::toCleanString($school_name),
        'school_email' => CraydelHelperFunctions::toEmailAddress($school_email),
        'school_phone' => CraydelHelperFunctions::toCleanString($school_phone),
        'school_address' => CraydelHelperFunctions::toCleanString($school_address),
        'school_physical_address' => CraydelHelperFunctions::toCleanString($school_physical_address),
        'school_website_url' => CraydelHelperFunctions::toCleanString($school_website_url),
        'temp_school_logo_url' => $_validate_school_logo->data->image_path ?? null,
        'temp_school_inverse_logo_url' => $_validate_school_inverse_logo->data->image_path ?? null,
        'parent_school_id' =>CraydelHelperFunctions::toCleanString($parent_school_id),
        'status' => 1,
        'created_at' => Carbon::now()->toDateTimeString(),
        'updated_at' => Carbon::now()->toDateTimeString(),
        'created_by' => $user->email ?? null,
        'updated_by' => $user->email ?? null
      ]
    ));
  }
}
