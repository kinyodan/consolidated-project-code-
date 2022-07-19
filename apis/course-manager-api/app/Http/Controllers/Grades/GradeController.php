<?php

namespace App\Http\Controllers\Grades;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Grades\Commands\BuildGradeCommandController;
use App\Http\Controllers\Grades\Commands\CreateGradeCommandController;
use App\Http\Controllers\Grades\Commands\DeleteGradeCommandController;
use App\Http\Controllers\Grades\Commands\UpdateGradeCommandController;
use App\Http\Controllers\Grades\Queries\GetGradeQueryController;
use App\Http\Controllers\Grades\Queries\ListGradeQueryController;
use App\Http\Controllers\Grades\Queries\SelectAGradeQueryController;
use App\Http\Controllers\Traits\CanCache;
use App\Http\Controllers\Traits\CanLog;
use App\Http\Controllers\Traits\CanPaginate;
use App\Http\Controllers\Traits\CanRespond;
use App\Http\Controllers\Traits\CanUploadImage;
use App\Models\Grade;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Helpers\CraydelInternalResponseHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Js;

class GradeController
{

    use CanLog, CanCache, CanPaginate, CanRespond;

    private BuildGradeCommandController $buildGradeCommandController;
    private ListGradeQueryController $listGradeQueryController;
    private CreateGradeCommandController $createGradeCommandController;
    private GetGradeQueryController $getGradeQueryController;
    private SelectAGradeQueryController $selectAGradeQueryController;
    private UpdateGradeCommandController $updateGradeCommandController;
    private DeleteGradeCommandController  $deleteGradeCommandController;

    /**
     * Constructor
     */
    public function __construct()
    {

        $this->buildGradeCommandController = new BuildGradeCommandController($this);
        $this->listGradeQueryController = new ListGradeQueryController($this);
        $this->createGradeCommandController = new CreateGradeCommandController($this);
        $this->getGradeQueryController = new GetGradeQueryController($this);
        $this->selectAGradeQueryController = new SelectAGradeQueryController($this);
        $this->updateGradeCommandController = new UpdateGradeCommandController($this);
        $this->deleteGradeCommandController = new DeleteGradeCommandController($this);


    }

    public function build(): JsonResponse
    {
        return $this->buildGradeCommandController->build();
    }

    public function grades(Request $request): JsonResponse
    {
        return $this->listGradeQueryController->get($request);
    }

    public function create(Request $request): JsonResponse
    {
        return $this->createGradeCommandController->create($request);
    }

    public function select(string $country_id): JsonResponse
    {
        return $this->getGradeQueryController->get($country_id);
    }

    public function selectGrade(string $grade_id): JsonResponse
    {
        return $this->selectAGradeQueryController->get($grade_id);
    }

    public function update(Request $request, $grade_id): JsonResponse
    {
        return $this->updateGradeCommandController->update($request,$grade_id);
    }

    public function  delete(string $grade_id): JsonResponse
    {
        return $this->deleteGradeCommandController->delete($grade_id);
    }

}
