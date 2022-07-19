<?php

namespace App\Http\Controllers\ManageLicenses;

use App\Http\Controllers\ManageLicenses\Commands\AddLicensesCommandsController;
use App\Http\Controllers\ManageLicenses\Queries\ListLicensesQueryController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ManageLicensesController
{
  
  private ListLicensesQueryController $listLicensesQueryController;
  private  AddLicensesCommandsController  $addLicensesCommandsController;
  
  
  /**
   * Constructor
   */
  public function __construct()
  {
    
    $this->listLicensesQueryController = new ListLicensesQueryController();
    $this->addLicensesCommandsController = new AddLicensesCommandsController();
    
  }
  
  /**
   * List the licenses
   *
   * @param Request $request
   * @return JsonResponse
   */
  public function licenses(Request $request): JsonResponse
  {
    return $this->listLicensesQueryController->handle($request);
  }
  
  /**
   * Add Licenses to a certain school
   *
   * @param Request $request
   * @param String $school_code
   * @return JsonResponse
   */
  
  public function add(Request $request, String $school_code): JsonResponse
  {
    return $this->addLicensesCommandsController->handle($request,$school_code);
  }
  
  
  
}