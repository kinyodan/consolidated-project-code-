<?php

namespace App\Http\Controllers\ManageStreams;

use App\Http\Controllers\ManageStreams\Commands\AddSchoolStreamCommandController;
use App\Http\Controllers\ManageStreams\Commands\DeleteSchoolStreamCommandController;
use App\Http\Controllers\ManageStreams\Commands\UpdateSchoolStreamCommandController;
use App\Http\Controllers\ManageStreams\Queries\ListSchoolStreamsQueryController;
use App\Http\Controllers\ManageStreams\Queries\ShowSchoolStreamsDetailsQueryController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ManageStreamController
{
  private ListSchoolStreamsQueryController $listSchoolStreamsQueryController;
  private AddSchoolStreamCommandController $addSchoolStreamCommandController;
  private ShowSchoolStreamsDetailsQueryController $showSchoolStreamsDetailsQueryController;
  private UpdateSchoolStreamCommandController $updateSchoolStreamCommandController;
  private DeleteSchoolStreamCommandController $deleteSchoolStreamCommandController;
  
  
  /**
   * Constructor
   */
  public function __construct()
  {
    $this->listSchoolStreamsQueryController = new ListSchoolStreamsQueryController();
    $this->addSchoolStreamCommandController = new AddSchoolStreamCommandController();
    $this->showSchoolStreamsDetailsQueryController = new ShowSchoolStreamsDetailsQueryController();
    $this->updateSchoolStreamCommandController = new UpdateSchoolStreamCommandController();
    $this->deleteSchoolStreamCommandController = new DeleteSchoolStreamCommandController();
    
    
  }
  
  /**
   * @param Request $request
   * @param string $school_code
   * @return JsonResponse
   */
  public function streams(Request $request, string $school_code): JsonResponse
  {
    return $this->listSchoolStreamsQueryController->handle($request, $school_code);
  }
  
  public function add(Request $request, string $school_code): JsonResponse
  {
    return $this->addSchoolStreamCommandController->handle($request, $school_code);
  }
  
  public function show(Request $request, string $school_code, int $stream_id): JsonResponse
  {
    return $this->showSchoolStreamsDetailsQueryController->handle($request, $school_code, $stream_id);
  }
  
  public function update(Request $request, string $school_code, int $stream_id): JsonResponse
  {
    return $this->updateSchoolStreamCommandController->handle($request, $school_code, $stream_id);
  }
  
  public function delete(Request $request, string $school_code, int $stream_id): JsonResponse
  {
    return $this->deleteSchoolStreamCommandController->handle($request, $school_code, $stream_id);
  }
}