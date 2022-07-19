<?php

namespace App\Http\Controllers\ManageStreams\Commands;

use App\Http\Controllers\CraydelTypes\CraydelJSONResponseType;
use App\Http\Controllers\Helpers\CraydelHelperFunctions;
use App\Http\Controllers\Helpers\GetLoggedIUserHelper;
use App\Http\Controllers\Helpers\LanguageTranslationHelper;
use App\Http\Controllers\Traits\CanLog;
use App\Http\Controllers\Traits\CanRespond;
use App\Models\SchoolStream;
use App\Models\Student;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DeleteSchoolStreamCommandController
{
  use CanLog, CanRespond;
  
  /**
   * Add school class
   *
   * @param Request $request
   * @param string $school_code
   * @param int $stream_id
   * @return JsonResponse
   */
  public function handle(Request $request, string $school_code, int $stream_id): JsonResponse
  {
    try {
     
      $user = GetLoggedIUserHelper::getUser($request);
      $school = GetLoggedIUserHelper::getUserSchool($request, $school_code);
      
      $class_has_students = DB::table((new Student())->getTable())
        ->where('school_id', $school->id)
        ->where('stream_id', CraydelHelperFunctions::toNumbers($stream_id))
        ->exists();
      
      if ($class_has_students) {
        throw new Exception("You can not delete a stream with linked students.");
      }
      
      $saved = false;
      
      DB::transaction(function () use ($stream_id, $user, &$saved) {
        $current_stream = DB::table((new SchoolStream())->getTable())
          ->where('id', $stream_id)
          ->first();
        
        $saved = DB::table((new SchoolStream())->getTable())
          ->where('id', $stream_id)
          ->update([
            'stream_name' => CraydelHelperFunctions::createSoftDeleteValue($current_stream->stream_name),
            'stream_name_slug' => CraydelHelperFunctions::createSoftDeleteValue($current_stream->stream_name_slug),
            'is_deleted' => 1,
            'deleted_by' => $user->email ?? null,
            'deleted_at' => Carbon::now()->toDateTimeString(),
            'status' => 0
          ]);
      });
      
      if (!$saved) {
        throw new Exception("Error while deleting the stream name details.");
      }
      
      return $this->respondInJSON(new CraydelJSONResponseType(
        true,
        LanguageTranslationHelper::translate('streams.success.deleted')
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
