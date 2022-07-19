<?php

namespace App\Http\Controllers\ManageStudents\Commands;

use App\Http\Controllers\CraydelTypes\CraydelJSONResponseType;
use App\Http\Controllers\Helpers\CraydelHelperFunctions;
use App\Http\Controllers\Helpers\GetLoggedIUserHelper;
use App\Http\Controllers\Helpers\LanguageTranslationHelper;
use App\Http\Controllers\Traits\CanLog;
use App\Http\Controllers\Traits\CanRespond;
use App\Models\Student;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DeleteStudentDetailsCommandController
{
  use CanRespond, CanLog;
  
  public function handle(Request $request, string $school_code, int $student_id): JsonResponse
  {
    try {
      $user = GetLoggedIUserHelper::getUser($request);
      $school = GetLoggedIUserHelper::getUserSchool($request, $school_code);
      $saved = false;
      
      DB::transaction(function () use ($student_id, $user, $school, &$saved) {
        $student = DB::table((new Student())->getTable())
          ->where('id', $student_id)
          ->where('school_id', $school->id)
          ->first();
        
        $saved = DB::table((new Student())->getTable())
          ->where('id', $student_id)
          ->update([
            'student_name' => CraydelHelperFunctions::createSoftDeleteValue($student->student_name),
            'student_email' => CraydelHelperFunctions::createSoftDeleteValue($student->student_email),
            'student_phone' => CraydelHelperFunctions::createSoftDeleteValue($student->student_phone),
            'guardian_name' => CraydelHelperFunctions::createSoftDeleteValue($student->guardian_name),
            'guardian_mobile_number' => CraydelHelperFunctions::createSoftDeleteValue($student->guardian_mobile_number),
            'guardian_email' => CraydelHelperFunctions::createSoftDeleteValue($student->guardian_email),
            'is_deleted' => 1,
            'deleted_by' => $user->email ?? null,
            'deleted_at' => Carbon::now()->toDateTimeString(),
          ]);
      });
      
      if (!$saved) {
        throw new Exception("Error while deleting the class name details.");
      }
      
      return $this->respondInJSON(new CraydelJSONResponseType(
        true,
        LanguageTranslationHelper::translate('students.success.deleted')
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