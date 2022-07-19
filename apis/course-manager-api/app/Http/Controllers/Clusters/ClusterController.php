<?php

namespace App\Http\Controllers\Clusters;

use App\Http\Controllers\Clusters\Commands\CreateClusterCommandController;
use App\Http\Controllers\Clusters\Commands\DeleteClusterCommandController;
use App\Http\Controllers\Clusters\Commands\UpdateClusterCommandController;
use App\Http\Controllers\Clusters\Queries\EditClusterQueryController;
use App\Http\Controllers\Clusters\Queries\ListClusterQueryController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CanCache;
use App\Http\Controllers\Traits\CanLog;
use App\Http\Controllers\Traits\CanPaginate;
use App\Http\Controllers\Traits\CanRespond;
use App\Http\Controllers\Traits\CanUploadImage;
use App\Models\Cluster;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Helpers\CraydelInternalResponseHelper;
use Illuminate\Http\Request;

class ClusterController
{

    use CanUploadImage, CanLog, CanCache, CanPaginate, CanRespond;

    private ListClusterQueryController $listClusterQueryController;
    private CreateClusterCommandController $createClusterCommandController;
    private EditClusterQueryController $editClusterQueryController;
    private UpdateClusterCommandController $updateClusterCommandController;
    private DeleteClusterCommandController $deleteClusterCommandController;


    /**
     * Constructor
     */
    public function __construct()
    {

        $this->listClusterQueryController = new ListClusterQueryController($this);
        $this->createClusterCommandController = new CreateClusterCommandController($this);
        $this->editClusterQueryController = new EditClusterQueryController($this);
        $this->updateClusterCommandController = new UpdateClusterCommandController($this);
        $this->deleteClusterCommandController = new DeleteClusterCommandController($this);


    }

    public function clusters(Request $request): JsonResponse
    {
        return $this->listClusterQueryController->get($request);
    }

    public function create(Request $request): JsonResponse
    {
        return $this->createClusterCommandController->create($request);
    }

    public  function  edit(string $cluster_id): JsonResponse
    {
        return $this->editClusterQueryController->get($cluster_id);

    }
    public  function  update(Request $request, $cluster_id): JsonResponse
    {
        return $this->updateClusterCommandController->update($request,$cluster_id);

    }
    public  function  delete(string $cluster_id): JsonResponse
    {
        return $this->deleteClusterCommandController->delete($cluster_id);
    }


}
