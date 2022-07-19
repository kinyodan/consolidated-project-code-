<?php
namespace App\Http\Controllers\ManageClasses\Commands;

use App\Http\Controllers\Helpers\CraydelHelperFunctions;
use App\Http\Controllers\Helpers\CraydelInternalResponseHelper;
use App\Http\Controllers\Helpers\GetLoggedIUserHelper;
use App\Http\Controllers\Helpers\LanguageTranslationHelper;
use App\Http\Controllers\Traits\CanRespond;
use App\Models\Curriculum;
use App\Models\UniversalSchoolClass;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use \Respect\Validation\Validator as v;


class ValidateClassController
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
    $class_name = $request->input('class_name');
    $curriculum_id = $request->input('curriculum_id');

    if(!v::stringVal()->notEmpty()->validate($class_name)){
      return (new CraydelInternalResponseHelper(
        false,
        LanguageTranslationHelper::translate('classes.errors.missing_class')
      ));
    }
    if(!v::stringVal()->notEmpty()->validate($curriculum_id)){
      return (new CraydelInternalResponseHelper(
        false,
        LanguageTranslationHelper::translate('classes.errors.missing_curriculum_id')
      ));
    }
    $check_if_curriculum_existing = DB::table((new Curriculum())->getTable())
      ->where('id',$curriculum_id)
      ->exists();

    if(!$check_if_curriculum_existing){
      return (new CraydelInternalResponseHelper(
        false,
        LanguageTranslationHelper::translate('classes.errors.missing_curriculum_correct_id')
      ));
    }

    $check_if_duplicate = DB::table((new UniversalSchoolClass())->getTable())
      ->where('class_name_slug', CraydelHelperFunctions::slugifyString($class_name))
      ->exists();

    if($check_if_duplicate && !$is_update){
      return (new CraydelInternalResponseHelper(
        false,
        LanguageTranslationHelper::translate('classes.errors.class_name_exists')
      ));
    }

    return $this->internalResponse(new CraydelInternalResponseHelper(
      true,
      'Validated',[
        'class_name' => CraydelHelperFunctions::toCleanString($class_name),
        'class_name_slug' => CraydelHelperFunctions::slugifyString($class_name),
        'curriculum_id' => $curriculum_id,
        'is_active' => 1,
        'created_at' => Carbon::now()->toDateTimeString(),
        'updated_at' => Carbon::now()->toDateTimeString(),
        'created_by' => $user->email ?? null,
        'updated_by' => $user->email ?? null
      ]
    ));
  }
}
