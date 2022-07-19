<?php

namespace App\Http\Controllers\ManageStudents\Commands;

use App\Http\Controllers\CraydelTypes\CraydelJSONResponseType;
use App\Http\Controllers\Helpers\LanguageTranslationHelper;
use App\Http\Controllers\Traits\CanLog;
use App\Http\Controllers\Traits\CanRespond;
use App\Models\Student;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AddStudentDetailsCommandController
{
  
  use CanLog, CanRespond;
  
  /**
   * Add school class
   *
   * @param Request $request
   * @param string $school_code
   * @return JsonResponse
   */
  public function handle(Request $request, string $school_code): JsonResponse
  {
    try {
      Log::info($request);
      $validation = (new ValidateStudentDetailsCommandController())->validate($request,$school_code);
      if (!$validation->status) {
        throw new Exception($validation->message ?? "Error while validating the school details");
      }
      
      $saved = false;
      
      DB::transaction(function () use ($validation, &$saved) {
        $saved = DB::table((new Student())->getTable())
          ->insert($validation->data);
      });
      
      if (!$saved) {
        throw new Exception("Error while saving the school name details.");
      }
      
      return $this->respondInJSON(new CraydelJSONResponseType(
        true,
        LanguageTranslationHelper::translate('students.success.added')
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