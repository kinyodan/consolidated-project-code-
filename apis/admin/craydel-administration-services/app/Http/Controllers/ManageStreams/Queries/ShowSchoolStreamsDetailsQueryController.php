<?php

namespace App\Http\Controllers\ManageStreams\Queries;

use App\Http\Controllers\CraydelTypes\CraydelJSONResponseType;
use App\Http\Controllers\Helpers\CraydelHelperFunctions;
use App\Http\Controllers\Helpers\DateHelper;
use App\Http\Controllers\Helpers\GetLoggedIUserHelper;
use App\Http\Controllers\Helpers\LanguageTranslationHelper;
use App\Http\Controllers\Traits\CanCache;
use App\Http\Controllers\Traits\CanLog;
use App\Http\Controllers\Traits\CanPaginate;
use App\Http\Controllers\Traits\CanRespond;
use App\Models\SchoolStream;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;

class ShowSchoolStreamsDetailsQueryController
{
  use CanCache, CanRespond, CanLog, CanPaginate;
  
  /**
   * Handle the classes list quest
   *
   * @param Request $request
   * @param string $school_code
   * @return JsonResponse
   */
  public function handle(Request $request, string $school_code,int $stream_id): JsonResponse
  {
    try {
      $school = GetLoggedIUserHelper::getUserSchool($request, $school_code);
      
      $stream = DB::table((new SchoolStream())->getTable())
        ->where('status', 1)
        ->where('school_id', $school->id)
        ->where('id',$stream_id)
        ->get();
      
      return $this->respondInJSON(new CraydelJSONResponseType(
        true,
        LanguageTranslationHelper::translate('streams.success.listed'),
        (object)[
          'items' => collect($stream)->map(function ($stream) {
            return [
              'id' => $stream->id ?? null,
              'stream_name' => $stream->stream_name ?? null,
              'school_id' => $stream->school_id ?? null,
              'stream_name_slug' => $stream->stream_name_slug ?? null,
              'created_at' => isset($stream->created_at) && CraydelHelperFunctions::isDate($stream->created_at) ?
                DateHelper::makeDisplayDateTime($stream->created_at, 'd-m-Y') : null,
              'updated_at' => isset($stream->updated_at) && CraydelHelperFunctions::isDate($stream->updated_at) ?
                DateHelper::makeDisplayDateTime($stream->updated_at, 'd-m-Y') : null,
            ];
          }),
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
