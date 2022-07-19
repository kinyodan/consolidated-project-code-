<?php

namespace App\Http\Controllers\Helpers;

use App\Http\Controllers\Traits\CanCache;
use App\Http\Controllers\Traits\CanLog;
use App\Models\School;
use Exception;
use Illuminate\Http\Request;

class GetLoggedIUserHelper
{
  
  use CanCache,CanLog;
  /**
   * Get current user
   *
   * @param Request $request
   *
   * @return object|null
   */
  public static function getUser(Request $request): ?object
  {
    try {
      $user = $request->user();
      
      if ($user) {
        return $user;
      } else {
        return null;
      }
    } catch (\Exception $exception) {
      return null;
    }
  }
  
  public static function getUserSchool(Request $request, string $school_code): ?object
  {
    try {
      $user = self::getUser($request);
      $school = self::cache("school_details_" . $school_code);
      
      if (is_null($school)) {
        $school = School::where('school_code', $school_code)->first();
      }
      
      return self::cache("school_details_" . $school_code, $school);
    } catch (Exception $exception) {
      (new self())->logException($exception);
      return null;
    }
  }
  
  /**
   * Get user schools
   *
   * @param Request $request
   * @return object|null
   */
  public static function getUserSchools(Request $request): ?object
  {
    try {
      $user = self::getUser($request);
      $schools = self::cache($user->user_code . "_schools");
      
      if (is_null($schools)) {
        $schools = $user->schools;
      }
      
      return self::cache($user->user_code . "_schools", $schools);
    } catch (Exception $exception) {
      (new self())->logException($exception);
      return null;
    }
  }
}
