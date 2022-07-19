<?php

namespace App\Http\Controllers\ManageGraduationYears\Commands;

use App\Http\Controllers\Helpers\CraydelHelperFunctions;
use App\Http\Controllers\Helpers\CraydelInternalResponseHelper;
use App\Http\Controllers\Helpers\GetLoggedIUserHelper;
use App\Http\Controllers\Helpers\LanguageTranslationHelper;
use App\Http\Controllers\Traits\CanRespond;
use App\Models\GraduationYear;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use \Respect\Validation\Validator as v;


class ValidateGraduationYearsController
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
    $year = $request->input('year');
    $description = $request->input('description');
    if (!v::stringVal()->notEmpty()->validate($year)) {
      return (new CraydelInternalResponseHelper(
        false,
        LanguageTranslationHelper::translate('years.errors.missing_name')
      ));
    }
    if (!v::stringVal()->notEmpty()->validate($description)) {
      return (new CraydelInternalResponseHelper(
        false,
        LanguageTranslationHelper::translate('years.errors.missing_description')
      ));
    }
    $check_if_graduation_year_exists = DB::table((new GraduationYear())->getTable())
      ->where('year', CraydelHelperFunctions::slugifyString($year))
      ->exists();
      
    if ($check_if_graduation_year_exists && !$is_update) {
      return (new CraydelInternalResponseHelper(
        false,
        LanguageTranslationHelper::translate('years.errors.name_exists')
      ));
    }
    
    return $this->internalResponse(new CraydelInternalResponseHelper(
      true,
      'Validated', [
        'year' => CraydelHelperFunctions::toCleanString($year),
        'description' => CraydelHelperFunctions::toCleanString($description),
        'is_active' => 1,
        'created_at' => Carbon::now()->toDateTimeString(),
        'updated_at' => Carbon::now()->toDateTimeString(),
        'created_by' => $user->email ?? null,
        'updated_by' => $user->email ?? null
      ]
    ));
  }
}