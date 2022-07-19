<?php

namespace App\Http\Controllers\ManageSchools\Commands;

use App\Http\Controllers\CraydelTypes\CraydelJSONResponseType;
use App\Http\Controllers\Helpers\LanguageTranslationHelper;
use App\Http\Controllers\Traits\CanLog;
use App\Http\Controllers\Traits\CanRespond;
use App\Http\Controllers\Traits\CanUploadImage;
use App\Jobs\PushImageToCdnJob;
use App\Models\School;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AddSchoolDetailsCommandController
{
  use CanLog, CanRespond, CanUploadImage;
  
  /**
   * Add school class
   *
   * @param Request $request
   * @return JsonResponse
   */
  public function handle(Request $request): JsonResponse
  {
    try {
      
      $validation = (new ValidateSchoolDetailsCommandController())->handle($request);
      if (!$validation->status) {
        throw new Exception($validation->message ?? "Error while validating the school details");
      }
  
      $school_id = null;
      
      DB::transaction(function () use ($validation, &$school_id) {
        $school_id = DB::table((new School())->getTable())
          ->insertGetId($validation->data);
      });
      
      if (!$school_id) {
        throw new Exception("Error while saving the school name details.");
      }
  
      $school_code = DB::table((new School())->getTable())
        ->where('id', $school_id)
        ->value('school_code');
      
      dispatch(new PushImageToCdnJob($school_code));
      
      return $this->respondInJSON(new CraydelJSONResponseType(
        true,
        LanguageTranslationHelper::translate('schools.success.added')
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
