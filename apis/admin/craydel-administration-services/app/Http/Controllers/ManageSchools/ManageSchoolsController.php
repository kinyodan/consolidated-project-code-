<?php

namespace App\Http\Controllers\ManageSchools;

use App\Http\Controllers\ManageSchools\Queries\ListSchoolsCurriculumController;
use App\Http\Controllers\ManageSchools\Queries\ListSchoolStreamsQueryController;
use App\Http\Controllers\ManageSchools\Queries\ShowSchoolDetailsQueryController;
use App\Http\Controllers\ManageSchools\Commands\AddSchoolDetailsCommandController;
use App\Http\Controllers\ManageSchools\Commands\DeleteSchoolDetailsCommandController;
use App\Http\Controllers\ManageSchools\Commands\UpdateSchoolDetailsCommandController;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\ManageSchools\Queries\ListSchoolsQueryController;
use Illuminate\Http\Request;

class ManageSchoolsController
{
  
  private ListSchoolsQueryController $listSchoolsQueryController;
  private AddSchoolDetailsCommandController $addSchoolDetailsCommandController;
  private ShowSchoolDetailsQueryController $showSchoolDetailsQueryController;
  private ListSchoolsCurriculumController  $listSchoolsCurriculumController;
  private UpdateSchoolDetailsCommandController $updateSchoolDetailsCommandController;
  private DeleteSchoolDetailsCommandController $deleteSchoolDetailsCommandController;
  private ListSchoolStreamsQueryController  $listSchoolStreamsQueryController;
  
  /**
   * @var array $allowed_image_extensions
   */
  public static array $allowed_image_extensions = [
    'png',
    'jpeg',
    'jpg'
  ];
  /**
   * Constructor
   */
  public function __construct()
  {
    $this->listSchoolsQueryController = new ListSchoolsQueryController();
    $this->addSchoolDetailsCommandController = new AddSchoolDetailsCommandController();
    $this->showSchoolDetailsQueryController = new ShowSchoolDetailsQueryController();
    $this->updateSchoolDetailsCommandController = new UpdateSchoolDetailsCommandController();
    $this->deleteSchoolDetailsCommandController = new DeleteSchoolDetailsCommandController();
    $this->listSchoolsCurriculumController = new ListSchoolsCurriculumController();
    $this->listSchoolStreamsQueryController = new ListSchoolStreamsQueryController();
    
    
  }
  
  /**
   * List the school classes
   *
   * @param Request $request
   * @return JsonResponse
   */
  public function schools(Request $request): JsonResponse
  {
    return $this->listSchoolsQueryController->handle($request);
  }
  
 
  
  public function add(Request $request): JsonResponse
  {
    return $this->addSchoolDetailsCommandController->handle($request);
  }
  public function curriculums(Request $request, $school_id): JsonResponse
  {
    return $this->listSchoolsCurriculumController->handle($request, $school_id);
  }
  
  
  public function show(Request $request, $school_id): JsonResponse
  {
    return $this->showSchoolDetailsQueryController->handle($request, $school_id);
  }
  
  public function update(Request $request, $school_id): JsonResponse
  {
    return $this->updateSchoolDetailsCommandController->handle($request, $school_id);
  }
  
  public function delete(Request $request, $school_id): JsonResponse
  {
    return $this->deleteSchoolDetailsCommandController->handle($request, $school_id);
  }
  
  public function streams(Request $request, $school_id): JsonResponse
  {
    return $this->listSchoolStreamsQueryController->handle($request, $school_id);
  }
  
  
  
  
}
