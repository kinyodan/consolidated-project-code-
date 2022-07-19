<?php

namespace App\Http\Controllers\ManageClasses\Commands;

use App\Http\Controllers\CraydelTypes\CraydelJSONResponseType;
use App\Http\Controllers\Helpers\CountryHelper;
use App\Http\Controllers\Helpers\CurriculumHelper;
use App\Http\Controllers\Helpers\LanguageTranslationHelper;
use App\Http\Controllers\Helpers\ManageClassHelper;
use App\Http\Controllers\Helpers\YearHelper;
use App\Http\Controllers\Traits\CanLog;
use App\Http\Controllers\Traits\CanRespond;
use App\Models\Curriculum;
use App\Models\UniversalSchoolClass;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BuildCommandController
{
  use CanLog, CanRespond;
  
  /**
   * Add school class
   *
   * @return JsonResponse
   */
  public function handle(): JsonResponse
  {
    try {
      
      
      return $this->respondInJSON(new CraydelJSONResponseType(
        true,
        LanguageTranslationHelper::translate('classes.success.build'), [
          'classes' => ManageClassHelper::classes(),
          'countries' => CountryHelper::countries(),
          'curriculums' => CurriculumHelper::curriculums(),
          'years' => YearHelper::years(),
        ]
      ));
    } catch (Exception $exception) {
      self::logException($exception);
      
      return $this->respondInJSON(new CraydelJSONResponseType(
        false,
        $exception->getMessage()
      ));
    }
  }
}
