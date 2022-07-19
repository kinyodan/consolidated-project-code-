<?php

namespace App\Http\Controllers\ManageStudents\Commands;

use App\Http\Controllers\Helpers\CraydelHelperFunctions;
use App\Http\Controllers\Helpers\CraydelInternalResponseHelper;
use App\Http\Controllers\Helpers\GetLoggedIUserHelper;
use App\Http\Controllers\Helpers\ParsePhoneNumberHelper;
use App\Http\Controllers\Traits\CanLog;
use App\Http\Controllers\Traits\CanRespond;
use App\Models\Student;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Respect\Validation\Validator as v;

class ValidateStudentDetailsCommandController
{
  use CanLog, CanRespond;
  
  /**
   * Handle student validation
   * @throws Exception
   */
  public function validate(Request $request, string $school_code, bool $is_update = false): CraydelInternalResponseHelper
  {
    try {
     
      $user = GetLoggedIUserHelper::getUser($request);
      $school = GetLoggedIUserHelper::getUserSchool($request, $school_code);
      $student_image = $request->input('student_image');
      $guardian_profile_photo = $request->input('guardian_profile_photo');
      $student_code = $request->input('student_code');
      $student_name = $request->input('student_name');
      $student_email = $request->input('student_email');
      $date_of_birth = $request->input('date_of_birth');
      $gender = $request->input('gender');
      $nationality = $request->input('nationality');
      $student_phone = $request->input('student_phone');
      $student_phone_country_code = $request->input('student_phone_country_code');
      $student_address = $request->input('student_address');
      $school_id = $school->id;
      $curriculum_id = $request->input('curriculum_id');
      $class_id = $request->input('class_id');
      $stream_id = $request->input('stream_id');
      $date_enrolled = $request->input('date_enrolled');
      $year_id = $request->input('year_id');
      $guardian_name = $request->input('guardian_name');
      $guardian_mobile_number = $request->input('guardian_mobile_number');
      $guardian_mobile_number_country_code = $request->input('guardian_mobile_number_country_code');
      $guardian_email = $request->input('guardian_email');
      $guardian_student_relationship = $request->input('guardian_student_relationship');
      $issue_license = $request->input('issue_license');
      
      if (!v::stringVal()->notEmpty()->validate($student_name)) {
        throw new Exception('Missing or invalid student name');
      }
      
      if (!v::optional(v::email())->validate($student_email)) {
        throw new Exception('Missing or invalid student email');
      }
      
      if (!v::optional(v::date())->validate($date_of_birth)) {
        throw new Exception('Invalid student date of birth');
      }
      
      if (!v::stringVal()->validate($gender)) {
        throw new Exception('Missing student gender value');
      }
      
      if (!v::stringVal()->validate($nationality)) {
        throw new Exception('Missing student nationality value');
      }
      
      if (!v::optional(v::stringVal())->validate($student_phone)) {
        throw new Exception('Missing student phone number');
      } else {
        if (!v::stringVal()->notEmpty()->validate($student_phone_country_code)) {
          throw new Exception('Missing student phone country code');
        }
        
        $_student_phone = ParsePhoneNumberHelper::makeNationalizedMobileNumber($student_phone_country_code, $student_phone);
        
        if (CraydelHelperFunctions::isNull($_student_phone)) {
          throw new Exception('Invalid student phone number');
        }
      }
      
      if (!v::optional(v::stringVal())->validate($student_address)) {
        throw new Exception('Invalid student address');
      }
      
      if (!v::intVal()->notEmpty()->validate($school_id)) {
        throw new Exception('Missing target school');
      }
      
      if (!v::intVal()->notEmpty()->validate($curriculum_id)) {
        throw new Exception('Missing target curriculum');
      }
      
      if (!v::intVal()->notEmpty()->validate($class_id)) {
        throw new Exception('Missing target class');
      }
      
      if (!v::intVal()->notEmpty()->validate($stream_id)) {
        throw new Exception('Missing target stream');
      }
      
      if (!v::intVal()->notEmpty()->validate($year_id)) {
        throw new Exception('Missing target graduation year');
      }
      
      if (!v::optional(v::date())->validate($date_enrolled)) {
        throw new Exception('Invalid enrollment date');
      }
      
      if (!v::stringVal()->notEmpty()->validate($guardian_name)) {
        throw new Exception('Missing guardian name');
      }
      
      if (!v::stringVal()->notEmpty()->validate($guardian_mobile_number)) {
        throw new Exception('Missing guardian phone number');
      } else {
        if (!v::stringVal()->notEmpty()->validate($guardian_mobile_number_country_code)) {
          throw new Exception('Missing guardian phone number country code');
        }
        
        $_guardian_phone = ParsePhoneNumberHelper::makeNationalizedMobileNumber($guardian_mobile_number_country_code, $guardian_mobile_number);
        
        if (CraydelHelperFunctions::isNull($_guardian_phone)) {
          throw new Exception('Invalid guardian phone number');
        }
      }
      
      if (!v::optional(v::email())->validate($guardian_email)) {
        throw new Exception('Missing or invalid guardian email');
      }
      return $this->internalResponse(new CraydelInternalResponseHelper(
        true,
        'Validated', [
          'student_code' => CraydelHelperFunctions::toCleanString($student_code) ?? null,
          'student_image' => CraydelHelperFunctions::toCleanString($student_image) ?? null,
          'guardian_profile_photo' => CraydelHelperFunctions::toCleanString($guardian_profile_photo) ?? null,
          'student_name' => CraydelHelperFunctions::toCleanString($student_name),
          'student_email' => CraydelHelperFunctions::toEmailAddress($student_email),
          'date_of_birth' => CraydelHelperFunctions::toCleanString($date_of_birth),
          'gender' => CraydelHelperFunctions::toCleanString($gender),
          'nationality' => CraydelHelperFunctions::toCleanString($nationality),
          'student_phone' => CraydelHelperFunctions::toCleanString($_student_phone),
          'student_address' => CraydelHelperFunctions::toCleanString($student_address),
          'curriculum_id' => CraydelHelperFunctions::toCleanString($curriculum_id),
          'class_id' => CraydelHelperFunctions::toCleanString($class_id),
          'stream_id' => CraydelHelperFunctions::toCleanString($stream_id),
          'date_enrolled' => CraydelHelperFunctions::toCleanString($date_enrolled),
          'year_id' => CraydelHelperFunctions::toCleanString($year_id),
          'guardian_name' => CraydelHelperFunctions::toCleanString($guardian_name),
          'guardian_mobile_number' => CraydelHelperFunctions::toCleanString($_guardian_phone),
          'guardian_mobile_number_country_code' => CraydelHelperFunctions::toCleanString($guardian_mobile_number_country_code),
          'guardian_email' => CraydelHelperFunctions::toEmailAddress($guardian_email),
          'guardian_student_relationship' => CraydelHelperFunctions::toCleanString($guardian_student_relationship),
          'school_id' => $school->id ?? null,
          'created_at' => Carbon::now()->toDateTimeString(),
          'updated_at' => Carbon::now()->toDateTimeString(),
          'created_by' => $user->email ?? null,
          'updated_by' => $user->email ?? null
        ]
      ));
    } catch (Exception $exception) {
      self::logException($exception);
      
      return $this->internalResponse(new CraydelInternalResponseHelper(
        false,
        $exception->getMessage()
      ));
    }
  }
  
  /**
   * Check if the email address is duplicate
   *
   * @param string $email
   * @param bool $is_update
   * @param string|null $student_code
   * @return bool
   */
  protected function _emailIsDuplicate(string $email, bool $is_update, string $student_code = null): bool
  {
    if (CraydelHelperFunctions::isNull($email)) {
      return false;
    }
    
    $check = DB::table((new Student())->getTable())
      ->where('student_email', $email);
    
    if ($is_update && $student_code) {
      return $check->where('student_code', '!=', $student_code)->exists();
    } else {
      return $check->exists();
    }
  }
  
  /**
   * Check if the guardian email is duplicate
   *
   * @param string $email
   * @param int $school_id
   * @return bool
   */
  protected function _guardianEmailIsDuplicate(string $email, int $school_id): bool
  {
    if (CraydelHelperFunctions::isNull($email)) {
      return false;
    }
    
    return DB::table((new Student())->getTable())
      ->where('school_id', $school_id)
      ->where('guardian_email', $email)
      ->exists();
  }
  
  /**
   * Check if the student phone is duplicate
   *
   * @param string $mobile_number
   * @param bool $is_update
   * @param string|null $student_code
   * @return bool
   */
  protected function _studentMobileNumberIsDuplicate(string $mobile_number, bool $is_update, string $student_code = null): bool
  {
    if (CraydelHelperFunctions::isNull($mobile_number)) {
      return false;
    }
    
    $check = DB::table((new Student())->getTable())
      ->where('student_phone', $mobile_number);
    
    if ($is_update && $student_code) {
      return $check->where('student_code', '!=', $student_code)->exists();
    } else {
      return $check->exists();
    }
  }
  
  /**
   * Check if the guardian mobile number is duplicate
   *
   * @param string $mobile_number
   * @param int $school_id
   * @return bool
   */
  protected function _guardianMobileNumberIsDuplicate(string $mobile_number, int $school_id): bool
  {
    if (CraydelHelperFunctions::isNull($mobile_number)) {
      return false;
    }
    
    return DB::table((new Student())->getTable())
      ->where('school_id', $school_id)
      ->where('guardian_mobile_number', $mobile_number)
      ->exists();
  }
}