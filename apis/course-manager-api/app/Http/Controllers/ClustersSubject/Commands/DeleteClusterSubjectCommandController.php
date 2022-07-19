<?php

namespace App\Http\Controllers\ClustersSubject\Commands;

use App\Http\Controllers\ClustersSubject\ClusterSubjectController;
use App\Http\Controllers\CraydelTypes\CraydelJSONResponseType;
use App\Http\Controllers\Helpers\CraydelHelperFunctions;
use App\Http\Controllers\Helpers\LanguageTranslationHelper;
use App\Http\Controllers\Traits\CanPaginate;
use App\Models\ClusterSubject;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;

class DeleteClusterSubjectCommandController
{


    /**
     * @var ClusterSubjectController
     */
    protected ClusterSubjectController $clusterSubjectController;

    /**
     * Constructor
     * @param ClusterSubjectController $clusterSubjectController
     */
    public function __construct(ClusterSubjectController $clusterSubjectController)
    {
        $this->clusterSubjectController = $clusterSubjectController;
    }


    public function delete(?string $cluster_subject_id): JsonResponse
    {
        try {


            $cluster = ClusterSubject::find($cluster_subject_id);
            $cluster->delete();

            return $this->clusterSubjectController->respondInJSON(new CraydelJSONResponseType(
                true,
                LanguageTranslationHelper::translate('clusters.success.is_deleted')
            ));

        } catch (\Exception $exception) {
            $this->clusterSubjectController->logException($exception);
            return $this->clusterSubjectController->respondInJSON((new CraydelJSONResponseType(
                false,
                LanguageTranslationHelper::translate('clusters.errors.is_deleted')
            )));
        }
    }
}
