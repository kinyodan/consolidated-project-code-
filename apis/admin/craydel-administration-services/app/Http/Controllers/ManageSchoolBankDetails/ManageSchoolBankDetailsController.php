<?php

namespace App\Http\Controllers\ManageSchoolBankDetails;

use App\Http\Controllers\ManageSchoolBankDetails\Commands\AddSchoolBankDetailsCommandController;
use App\Http\Controllers\ManageSchoolBankDetails\Commands\DeleteSchoolBankDetailsCommandController;
use App\Http\Controllers\ManageSchoolBankDetails\Commands\UpdateSchoolBankDetailsCommandController;
use App\Http\Controllers\ManageSchoolBankDetails\Queries\ListSchoolBankDetailsQueryController;
use App\Http\Controllers\ManageSchoolBankDetails\Queries\ShowSchoolBankDetailsQueryController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ManageSchoolBankDetailsController
{
  private ListSchoolBankDetailsQueryController $listSchoolBankDetailsQueryController;
  private AddSchoolBankDetailsCommandController $addSchoolBankDetailsCommandController;
  private ShowSchoolBankDetailsQueryController  $showSchoolBankDetailsQueryController;
  private UpdateSchoolBankDetailsCommandController $updateSchoolBankDetailsCommandController;
  private DeleteSchoolBankDetailsCommandController $deleteSchoolBankDetailsCommandController;
  
  
  /**
   * Constructor
   */
  public function __construct()
  {
    $this->listSchoolBankDetailsQueryController = new ListSchoolBankDetailsQueryController();
    $this->addSchoolBankDetailsCommandController = new AddSchoolBankDetailsCommandController();
    $this->showSchoolBankDetailsQueryController = new ShowSchoolBankDetailsQueryController();
    $this->updateSchoolBankDetailsCommandController = new UpdateSchoolBankDetailsCommandController();
    $this->deleteSchoolBankDetailsCommandController = new DeleteSchoolBankDetailsCommandController();
    
    
  }
  
  /**
   * List the school classes
   *
   * @param Request $request
   * @param string $school_code
   * @return JsonResponse
   */
  public function details(Request $request, string $school_code): JsonResponse
  {
    return $this->listSchoolBankDetailsQueryController->handle($request, $school_code);
  }
  
  public function add(Request $request, string $school_code): JsonResponse
  {
    return $this->addSchoolBankDetailsCommandController->handle($request, $school_code);
  }
  
  public function show(Request $request, string $school_code): JsonResponse
  {
    return $this->showSchoolBankDetailsQueryController->handle($request, $school_code);
  }
  
  public function update(Request $request, string $school_code, int $bank_detail_id): JsonResponse
  {
    return $this->updateSchoolBankDetailsCommandController->handle($request, $school_code,$bank_detail_id);
  }
  public function delete(Request $request, string $school_code,int $bank_detail_id): JsonResponse
  {
    return $this->deleteSchoolBankDetailsCommandController->handle($request, $school_code,$bank_detail_id);
  }
  
  
}