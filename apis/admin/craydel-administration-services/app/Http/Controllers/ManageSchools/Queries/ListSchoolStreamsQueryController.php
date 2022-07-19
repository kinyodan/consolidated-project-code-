<?php

namespace App\Http\Controllers\ManageSchools\Queries;

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
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;

class ListSchoolStreamsQueryController
{
  use CanPaginate,CanCache,CanRespond,CanLog;
  public function handle(Request $request, string $school_id): JsonResponse
  {
    try {
      $streams = DB::table((new SchoolStream())->getTable())
        ->where('status', 1)
        ->where('school_id', $school_id);
      
      $currentPage = $request->input('page');
      
      $currentPage = !empty($currentPage) ? $currentPage : 1;
      $this->currentPaginationPage = $currentPage;
      $this->totalNumberOfEntities = $streams->count('id');
      $this->itemsPerPage = config('craydle.items_per_page', 10);
      
      Paginator::currentPageResolver(function () use ($currentPage) {
        return $currentPage;
      });
      
      $streams = $streams
        ->orderBy('id', 'desc')
        ->simplePaginate($this->itemsPerPage);
      
      return $this->respondInJSON(new CraydelJSONResponseType(
        true,
        LanguageTranslationHelper::translate('streams.success.listed'),
        (object)[
          'items' => collect($streams->items())->map(function ($stream) {
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
          'items_per_page' => $this->itemsPerPage,
          'current_page' => $this->currentPaginationPage,
          'previous_page' => $this->previousPage(),
          'next_page' => $this->nextPage(),
          'number_of_pages' => $this->getTotalNumberOfPages(),
          'items_count' => $this->totalNumberOfEntities
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