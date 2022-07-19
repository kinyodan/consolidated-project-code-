<?php

namespace App\Http\Controllers\ManageClasses;


use App\Http\Controllers\ManageClasses\Commands\BuildCommandController;
use App\Http\Controllers\ManageClasses\Queries\ListClassesQueryController;
use App\Http\Controllers\ManageClasses\Commands\AddClassCommandController;
use App\Http\Controllers\ManageClasses\Commands\UpdateClassCommandController;
use App\Http\Controllers\ManageClasses\Commands\DeleteClassCommandController;
use App\Http\Controllers\ManageClasses\Queries\ShowClassQueryController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ManageClassesController
{
  /**
   * @var ListClassesQueryController $listClassesQueryController
   */
  
  private ListClassesQueryController $listClassesQueryController;
  
  private AddClassCommandController $addClassCommandController;
  
  private ShowClassQueryController $showClassQueryController;
  
  private UpdateClassCommandController $updateClassCommandController;
  
  private DeleteClassCommandController $deleteClassCommandController;
  
  private BuildCommandController $buildCommandController;
  
  
  /**
   * Constructor
   */
  public function __construct()
  {
    $this->listClassesQueryController = new ListClassesQueryController();
    $this->addClassCommandController = new AddClassCommandController();
    $this->updateClassCommandController = new UpdateClassCommandController();
    $this->deleteClassCommandController = new DeleteClassCommandController();
    $this->showClassQueryController = new ShowClassQueryController();
    $this->buildCommandController = new BuildCommandController();
    
  }
  
  /**
   * List the school classes
   *
   * @return JsonResponse
   */
  public function build(): JsonResponse
  {
    return $this->buildCommandController->handle();
  }
  
  public function classes(Request $request): JsonResponse
  {
    return $this->listClassesQueryController->handle($request);
  }
  
  public function add(Request $request): JsonResponse
  {
    return $this->addClassCommandController->handle($request);
  }
  
  public function update(Request $request, int $class_id): JsonResponse
  {
    return $this->updateClassCommandController->handle($request, $class_id);
  }
  
  public function delete(Request $request, int $class_id): JsonResponse
  {
    return $this->deleteClassCommandController->handle($request, $class_id);
  }
  
  public function show(Request $request, int $class_id): JsonResponse
  {
    return $this->showClassQueryController->handle($request, $class_id);
  }
}
