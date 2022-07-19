<?php

namespace App\Http\Controllers\ClustersSubject;

use App\Http\Controllers\ClustersSubject\Commands\BuildNewClusterSubjectCommandController;
use App\Http\Controllers\ClustersSubject\Commands\CreateClusterSubjectCommandController;
use App\Http\Controllers\ClustersSubject\Commands\DeleteClusterSubjectCommandController;
use App\Http\Controllers\ClustersSubject\Commands\UpdateClusterSubjectCommandController;
use App\Http\Controllers\ClustersSubject\Queries\GetClustersSubjectQueryController;
use App\Http\Controllers\ClustersSubject\Queries\ListClustersSubjectQueryController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CanCache;
use App\Http\Controllers\Traits\CanLog;
use App\Http\Controllers\Traits\CanPaginate;
use App\Http\Controllers\Traits\CanRespond;
use App\Http\Controllers\Traits\CanUploadImage;
use App\Models\ClusterSubject;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Helpers\CraydelInternalResponseHelper;
use Illuminate\Http\Request;

class ClusterSubjectController
{

    use CanLog, CanCache, CanPaginate, CanRespond;

    private ListClustersSubjectQueryController $listClustersSubjectQueryController;
    private CreateClusterSubjectCommandController $createClusterSubjectCommandController;
    private GetClustersSubjectQueryController  $getClustersSubjectQueryController;
    private UpdateClusterSubjectCommandController  $updateClusterSubjectCommandController;
    private DeleteClusterSubjectCommandController $deleteClusterSubjectCommandController;
    private BuildNewClusterSubjectCommandController $buildNewClusterSubjectCommandController;



    /**
     * Constructor
     */
    public function __construct()
    {

        $this->listClustersSubjectQueryController = new ListClustersSubjectQueryController($this);
        $this->createClusterSubjectCommandController = new CreateClusterSubjectCommandController($this);
        $this->deleteClusterSubjectCommandController = new DeleteClusterSubjectCommandController($this);
        $this->getClustersSubjectQueryController = new GetClustersSubjectQueryController($this);
        $this->updateClusterSubjectCommandController = new UpdateClusterSubjectCommandController($this);
        $this->buildNewClusterSubjectCommandController = new BuildNewClusterSubjectCommandController($this);


    }


    public  function  build(): JsonResponse
    {
        return $this->buildNewClusterSubjectCommandController->build();
    }

    public function getClusterSubject(Request $request): JsonResponse
    {
        return $this->listClustersSubjectQueryController->get($request);
    }

    public function create(Request $request): JsonResponse
    {
        return $this->createClusterSubjectCommandController->create($request);
    }

    public  function edit(Request $request, ?string $cluster_subject_id): JsonResponse
    {
        return $this->getClustersSubjectQueryController->get($request,$cluster_subject_id);
    }

    public  function update(Request $request,?string $cluster_subject_id): JsonResponse
    {
        return $this->updateClusterSubjectCommandController->update($request,$cluster_subject_id);
    }


    public function delete(?string $cluster_subject_id): JsonResponse
    {
        return $this->deleteClusterSubjectCommandController->delete($cluster_subject_id);
    }

}
