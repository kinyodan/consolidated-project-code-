<?php

namespace App\Http\Controllers\Clusters\Commands;

use App\Http\Controllers\Clusters\ClusterController;
use App\Http\Controllers\CraydelTypes\CraydelJSONResponseType;
use App\Http\Controllers\Helpers\CraydelHelperFunctions;
use App\Http\Controllers\Helpers\LanguageTranslationHelper;
use App\Http\Controllers\Traits\CanPaginate;
use App\Models\Cluster;
use App\Models\ClusterSubject;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;

class DeleteClusterCommandController
{


    protected ClusterController  $clusterController;


    /**
     * @param ClusterController $clusterController
     */
    public function __construct(ClusterController $clusterController)
    {
        $this->clusterController = $clusterController;
    }


    public function delete(?string $cluster_id): JsonResponse
    {
        try {
            $count = ClusterSubject::where('cluster_id','=',$cluster_id)->count();
            if (!empty($count)) {
                return $this->clusterController->respondInJSON((new CraydelJSONResponseType(
                    false,
                    LanguageTranslationHelper::translate('clusters.errors.cant_deleted')
                )));
            }
            $cluster = Cluster::find($cluster_id);
            $cluster->delete();

            return $this->clusterController->respondInJSON(new CraydelJSONResponseType(
                true,
                LanguageTranslationHelper::translate('clusters.success.is_deleted')
            ));

        } catch (\Exception $exception) {
            $this->clusterController->logException($exception);
            return $this->clusterController->respondInJSON((new CraydelJSONResponseType(
                false,
                LanguageTranslationHelper::translate('clusters.errors.is_deleted')
            )));
        }
    }
}
