<?php

namespace App\Http\Controllers\ManageLicenses\Commands;

use App\Http\Controllers\Helpers\CraydelHelperFunctions;
use App\Http\Controllers\Helpers\CraydelInternalResponseHelper;
use App\Http\Controllers\Helpers\GetLoggedIUserHelper;
use App\Http\Controllers\Helpers\LanguageTranslationHelper;
use App\Http\Controllers\Traits\CanLog;
use App\Http\Controllers\Traits\CanRespond;
use App\Models\Country;
use App\Models\Curriculum;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Respect\Validation\Validator as v;

class ValidateLicenseDetailsCommandController
{
  use CanRespond,CanLog;
  public function handle(Request $request, string $school_code, bool $is_update = false): CraydelInternalResponseHelper
  {
    $user = GetLoggedIUserHelper::getUser($request);
    $school = GetLoggedIUserHelper::getUserSchool($request, $school_code);
    $allowed_license_count = $request->input('allowed_license_count');
    
    if(!v::stringVal()->notEmpty()->validate($allowed_license_count)){
      return (new CraydelInternalResponseHelper(
        false,
        LanguageTranslationHelper::translate('licenses.errors.allowed_license_count_missing')
      ));
    }
    
    
    return $this->internalResponse(new CraydelInternalResponseHelper(
      true,
      'Validated',[
        'allowed_license_count' => CraydelHelperFunctions::toCleanString($allowed_license_count),
        'school_id' => $school->id ?? null,
        'created_at' => Carbon::now()->toDateTimeString(),
        'updated_at' => Carbon::now()->toDateTimeString(),
        'created_by' => $user->email ?? null,
        'updated_by' => $user->email ?? null
      ]
    ));
  }

}