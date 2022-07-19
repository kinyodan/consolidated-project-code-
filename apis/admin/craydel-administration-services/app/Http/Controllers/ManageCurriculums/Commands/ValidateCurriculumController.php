<?php

namespace App\Http\Controllers\ManageCurriculums\Commands;

use App\Http\Controllers\Helpers\CountryHelper;
use App\Http\Controllers\Helpers\CraydelHelperFunctions;
use App\Http\Controllers\Helpers\CraydelInternalResponseHelper;
use App\Http\Controllers\Helpers\GetLoggedIUserHelper;
use App\Http\Controllers\Helpers\LanguageTranslationHelper;
use App\Http\Controllers\Traits\CanRespond;
use App\Models\Country;
use App\Models\Curriculum;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use \Respect\Validation\Validator as v;


class ValidateCurriculumController
{
  use CanRespond;
  
  /**
   * Validate
   *
   * @param Request $request
   * @param bool $is_update
   * @return CraydelInternalResponseHelper
   */
  public function handle(Request $request, bool $is_update = false): CraydelInternalResponseHelper
  {
    
    $user = GetLoggedIUserHelper::getUser($request);
    $curriculum_name = $request->input('curriculum_name');
    $curriculum_code = $request->input('curriculum_code');
    $country_code = $request->input('country_code');
    $is_global = $request->input('is_global');
    if (!v::stringVal()->notEmpty()->validate($curriculum_name)) {
      return (new CraydelInternalResponseHelper(
        false,
        LanguageTranslationHelper::translate('curriculum.errors.missing_name')
      ));
    }
    if (!v::stringVal()->notEmpty()->validate($country_code)) {
      return (new CraydelInternalResponseHelper(
        false,
        LanguageTranslationHelper::translate('curriculum.errors.missing_country_code')
      ));
    }
    $check_if_country_code_existing = DB::table((new Country())->getTable())
      ->where('code', CraydelHelperFunctions::slugifyString($country_code))
      ->exists();
    
    if (!$check_if_country_code_existing) {
      return (new CraydelInternalResponseHelper(
        false,
        LanguageTranslationHelper::translate('curriculum.errors.country_code_exists')
      ));
    }
    if (!v::stringVal()->notEmpty()->validate($curriculum_code)) {
      return (new CraydelInternalResponseHelper(
        false,
        LanguageTranslationHelper::translate('curriculum.errors.curriculum_code')
      ));
    }
    $check_if_duplicate = DB::table((new Curriculum())->getTable())
      ->where('curriculum_slug', CraydelHelperFunctions::slugifyString($curriculum_name))
      ->exists();
    
    if ($check_if_duplicate && !$is_update) {
      return (new CraydelInternalResponseHelper(
        false,
        LanguageTranslationHelper::translate('curriculum.errors.name_exists')
      ));
    }
    
    return $this->internalResponse(new CraydelInternalResponseHelper(
      true,
      'Validated', [
        'curriculum_name' => CraydelHelperFunctions::toCleanString($curriculum_name),
        'curriculum_slug' => CraydelHelperFunctions::slugifyString($curriculum_name),
        'country_id' => CountryHelper::getCountryId($country_code),
        'country_code' => $country_code,
        'curriculum_code' => $curriculum_code,
        'is_active' => 1,
        'is_global' => $is_global,
        'created_at' => Carbon::now()->toDateTimeString(),
        'updated_at' => Carbon::now()->toDateTimeString(),
        'created_by' => $user->email ?? null,
        'updated_by' => $user->email ?? null
      ]
    ));
  }
}
