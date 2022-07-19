<?php

namespace App\Http\Controllers\Clusters\Queries;

use App\Http\Controllers\Clusters\ClusterController;
use App\Http\Controllers\CraydelTypes\CraydelJSONResponseType;
use App\Http\Controllers\Helpers\CraydelHelperFunctions;
use App\Http\Controllers\Helpers\LanguageTranslationHelper;
use App\Models\Cluster;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;

class EditClusterQueryController
{

    protected ClusterController $clusterController;


    /**
     * @param ClusterController $clusterController
     */
    public function __construct(ClusterController $clusterController)
    {
        $this->clusterController = $clusterController;
    }


    public function get(?string $cluster_id): JsonResponse
    {
        try {
            $cluster = Cluster::findOrFail($cluster_id);
            return $this->clusterController->respondInJSON(new CraydelJSONResponseType(
                true,
                LanguageTranslationHelper::translate('clusters.success.is_selected'),
                $cluster
            ));

        } catch (\Exception $exception) {
            $this->clusterController->logException($exception);
            return $this->clusterController->respondInJSON((new CraydelJSONResponseType(
                false,
                LanguageTranslationHelper::translate('clusters.errors.is_selected')
            )));
        }
    }
}
