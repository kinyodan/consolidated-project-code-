<?php
namespace App\Http\Controllers\ManageStreams\Commands;

use App\Http\Controllers\Helpers\CraydelHelperFunctions;
use App\Http\Controllers\Helpers\CraydelInternalResponseHelper;
use App\Http\Controllers\Helpers\GetLoggedIUserHelper;
use App\Http\Controllers\Helpers\LanguageTranslationHelper;
use App\Http\Controllers\Traits\CanRespond;
use App\Models\SchoolStream;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Respect\Validation\Validator as v;

class ValidateSchoolStreamController
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
    $stream_name = $request->input('stream_name');
    
    if(!v::stringVal()->notEmpty()->validate($stream_name)){
      return (new CraydelInternalResponseHelper(
        false,
        LanguageTranslationHelper::translate('streams.errors.missing_name')
      ));
    }
    
    $check_if_duplicate = DB::table((new SchoolStream())->getTable())
      ->where('stream_name_slug', CraydelHelperFunctions::slugifyString($stream_name))
      ->where('school_id', $school->id)
      ->exists();
    
    if($check_if_duplicate && !$is_update){
      return (new CraydelInternalResponseHelper(
        false,
        LanguageTranslationHelper::translate('streams.errors.stream_name_exists')
      ));
    }
    
    return $this->internalResponse(new CraydelInternalResponseHelper(
      true,
      'Validated',[
        'stream_name' => CraydelHelperFunctions::toCleanString($stream_name),
        'stream_name_slug' => CraydelHelperFunctions::slugifyString($stream_name),
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
