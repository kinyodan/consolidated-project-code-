<?php

namespace App\Http\Controllers\Subjects;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Subjects\Commands\CreateSubjectCommandController;
use App\Http\Controllers\Subjects\Commands\DeleteSubjectCommandController;
use App\Http\Controllers\Subjects\Commands\UpdateSubjectCommandController;
use App\Http\Controllers\Subjects\Queries\GetSubjectQueryController;
use App\Http\Controllers\Subjects\Queries\SearchSubjectQueryController;
use App\Http\Controllers\Traits\CanCache;
use App\Http\Controllers\Traits\CanLog;
use App\Http\Controllers\Traits\CanPaginate;
use App\Http\Controllers\Traits\CanRespond;
use App\Http\Controllers\Traits\CanUploadImage;
use App\Models\Subject;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Helpers\CraydelInternalResponseHelper;
use App\Http\Controllers\Subjects\Queries\ListSubjectQueryController;
use Illuminate\Http\Request;

class SubjectController
{

    use CanUploadImage, CanLog, CanCache, CanPaginate, CanRespond;

    private ListSubjectQueryController $listSubjectQueryController;
    private CreateSubjectCommandController $createSubjectCommandController;
    private GetSubjectQueryController $getSubjectQueryController;
    private UpdateSubjectCommandController $updateSubjectCommandController;
    private DeleteSubjectCommandController $deleteSubjectCommandController;


    /**
     * Constructor
     */
    public function __construct()
    {

        $this->listSubjectQueryController = new ListSubjectQueryController($this);
        $this->createSubjectCommandController = new CreateSubjectCommandController($this);
        $this->getSubjectQueryController = new GetSubjectQueryController($this);
        $this->updateSubjectCommandController = new UpdateSubjectCommandController($this);
        $this->deleteSubjectCommandController = new DeleteSubjectCommandController($this);



    }



    public function subjects(Request $request): JsonResponse
    {
        return $this->listSubjectQueryController->get($request);
    }

    public function create(Request $request): JsonResponse
    {
        return $this->createSubjectCommandController->create($request);
    }

    public function edit(string $subject_id): JsonResponse
    {
        return $this->getSubjectQueryController->get($subject_id);
    }

    public function update(Request $request, string $subject_id): JsonResponse
    {
        return $this->updateSubjectCommandController->update($request, $subject_id);
    }

    /**
     * @param string $subject_id
     * @return JsonResponse
     */
    public function delete(string $subject_id): JsonResponse
    {
        return $this->deleteSubjectCommandController->delete($subject_id);
    }
}
