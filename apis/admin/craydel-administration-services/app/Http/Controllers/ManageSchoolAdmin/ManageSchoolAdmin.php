<?php

namespace App\Http\Controllers\ManageSchoolAdmin;
use App\Http\Controllers\ManageSchoolAdmin\Commands\AddSchoolAdminDetailsCommandController;
use App\Http\Controllers\ManageSchoolAdmin\Commands\DeleteSchoolAdminDetailsCommandController;
use App\Http\Controllers\ManageSchoolAdmin\Commands\UpdateSchoolAdminDetailsCommandController;
use App\Http\Controllers\ManageSchoolAdmin\Queries\ListSchoolAdminQueryController;
use App\Http\Controllers\ManageSchoolAdmin\Queries\ShowSchoolAdminQueryController;
use App\Http\Controllers\ManageSchoolBankDetails\Commands\UpdateSchoolBankDetailsCommandController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ManageSchoolAdmin
{

  Private ListSchoolAdminQueryController $listSchoolAdminQueryController;
  Private AddSchoolAdminDetailsCommandController $addSchoolAdminDetailsCommandController;
  Private ShowSchoolAdminQueryController $showSchoolAdminQueryController;
  Private UpdateSchoolAdminDetailsCommandController $updateSchoolAdminDetailsCommandController;
  Private DeleteSchoolAdminDetailsCommandController $deleteSchoolAdminDetailsCommandController;
  
  
  public function __construct()
  {
    $this->listSchoolAdminQueryController = new ListSchoolAdminQueryController();
    $this->showSchoolAdminQueryController = new ShowSchoolAdminQueryController();
    $this->addSchoolAdminDetailsCommandController = new AddSchoolAdminDetailsCommandController();
    $this->updateSchoolAdminDetailsCommandController = new UpdateSchoolAdminDetailsCommandController();
    $this->deleteSchoolAdminDetailsCommandController = new DeleteSchoolAdminDetailsCommandController();
  
  }
  
  public function admins(Request $request, string $school_code): JsonResponse
  {
    return $this->listSchoolAdminQueryController->handle($request, $school_code);
  }
  
  public function add(Request $request, string $school_code): JsonResponse
  {
    return $this->addSchoolAdminDetailsCommandController->handle($request, $school_code);
  }
  public function show(Request $request, string $school_code, int $school_admin_id): JsonResponse
  {
    return $this->showSchoolAdminQueryController->handle($request, $school_code,$school_admin_id);
  }
  public function update(Request $request, string $school_code, int $school_admin_id): JsonResponse
  {
    return $this->updateSchoolAdminDetailsCommandController->handle($request, $school_code,$school_admin_id);
  }
  public function delete(Request $request, string $school_code, int $school_admin_id): JsonResponse
  {
    return $this->deleteSchoolAdminDetailsCommandController->handle($request, $school_code,$school_admin_id);
  }
}