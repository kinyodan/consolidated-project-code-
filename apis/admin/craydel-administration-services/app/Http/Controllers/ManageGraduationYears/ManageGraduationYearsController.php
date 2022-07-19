<?php

namespace App\Http\Controllers\ManageGraduationYears;


use App\Http\Controllers\ManageGraduationYears\Commands\AddGraduationYearsCommandController;
use App\Http\Controllers\ManageGraduationYears\Queries\ListGraduationYearsQueryController;
use  App\Http\Controllers\ManageGraduationYears\Commands\UpdateGraduationYearCommandController;
use  App\Http\Controllers\ManageGraduationYears\Commands\DeleteGraduationYearCommandController;

use App\Http\Controllers\ManageGraduationYears\Queries\ShowGraduationYearsQueryController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ManageGraduationYearsController
{
  
  private ListGraduationYearsQueryController $listGraduationYearsQueryController;
  private AddGraduationYearsCommandController $addGraduationYearsCommandController;
  private  UpdateGraduationYearCommandController $updateGraduationYearCommandController;
  private  DeleteGraduationYearCommandController $deleteGraduationYearCommandController;
  private  ShowGraduationYearsQueryController $showGraduationYearsQueryController;
  
  
  /**
   * Constructor
   */
  public function __construct()
  {
    $this->listGraduationYearsQueryController = new ListGraduationYearsQueryController();
    $this->addGraduationYearsCommandController = new AddGraduationYearsCommandController();
    $this->updateGraduationYearCommandController = new UpdateGraduationYearCommandController();
    $this->deleteGraduationYearCommandController = new DeleteGraduationYearCommandController();
    $this->showGraduationYearsQueryController = new ShowGraduationYearsQueryController();
    
    
  }
  
  /**
   * List the school classes
   *
   * @param Request $request
   * @return JsonResponse
   */
  public function years(Request $request): JsonResponse
  {
    return $this->listGraduationYearsQueryController->handle($request);
  }
  
  public function add(Request $request): JsonResponse
  {
    return $this->addGraduationYearsCommandController->handle($request);
  }
  
  public  function  update(Request $request, int $year_id) :JsonResponse
  {
    return $this->updateGraduationYearCommandController->handle($request,$year_id);
  }
  public  function  delete(Request $request, int $year_id) :JsonResponse
  {
    return $this->deleteGraduationYearCommandController->handle($request,$year_id);
  }
  public  function  show(Request $request, int $year_id) :JsonResponse
  {
    return $this->showGraduationYearsQueryController->handle($request,$year_id);
  }
}
