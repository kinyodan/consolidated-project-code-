<?php

namespace App\Http\Controllers\ManageCurriculums;



use App\Http\Controllers\ManageCurriculums\Queries\ListCurriculumsClassesQueryController;
use App\Http\Controllers\ManageCurriculums\Queries\ListManageCurriculumsQueryController;
use App\Http\Controllers\ManageCurriculums\Commands\DeleteCurriculumCommandController;
use App\Http\Controllers\ManageCurriculums\Commands\AddCurriculumCommandController;
use  App\Http\Controllers\ManageCurriculums\Commands\UpdateCurriculumCommandController;
use App\Http\Controllers\ManageCurriculums\Queries\ShowCurriculumsQueryController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ManageCurriculumsController
{
 
  private ListManageCurriculumsQueryController $listManageCurriculumsQueryController;
  private AddCurriculumCommandController $addCurriculumCommandController;
  private  ShowCurriculumsQueryController $showCurriculumsQueryController;
  private  UpdateCurriculumCommandController $updateCurriculumCommandController;
  private  DeleteCurriculumCommandController $deleteCurriculumCommandController;
  private  ListCurriculumsClassesQueryController $listCurriculumsClassesQueryController;
  

  
  /**
   * Constructor
   */
  public function __construct()
  {
    $this->listManageCurriculumsQueryController = new ListManageCurriculumsQueryController();
    $this->addCurriculumCommandController = new AddCurriculumCommandController();
    $this->updateCurriculumCommandController = new UpdateCurriculumCommandController();
    $this->deleteCurriculumCommandController = new DeleteCurriculumCommandController();
    $this->showCurriculumsQueryController = new ShowCurriculumsQueryController();
    $this->listCurriculumsClassesQueryController = new ListCurriculumsClassesQueryController();
   
    
  }
  
  /**
   * List the school classes
   *
   * @param Request $request
   * @return JsonResponse
   */
  public function curriculums(Request $request): JsonResponse
  {
    return $this->listManageCurriculumsQueryController->handle($request);
  }
  public function add(Request $request): JsonResponse
  {
    return $this->addCurriculumCommandController->handle($request);
  }
  public function update(Request $request,int $curriculums_id): JsonResponse
  {
    return $this->updateCurriculumCommandController->handle($request,$curriculums_id);
  }
  public function delete(Request $request,int $curriculums_id): JsonResponse
  {
    return $this->deleteCurriculumCommandController->handle($request,$curriculums_id);
  }
  public function show(Request $request,int $curriculums_id): JsonResponse
  {
    return $this->showCurriculumsQueryController->handle($request,$curriculums_id);
  }
  
  public function classes(Request $request,int $curriculums_id): JsonResponse
  {
    return $this->listCurriculumsClassesQueryController->handle($request,$curriculums_id);
  }
}
