<?php

namespace App\Http\Controllers\Clusters\Commands;

use App\Http\Controllers\Clusters\ClusterController;
use App\Http\Controllers\CraydelTypes\CraydelJSONResponseType;
use App\Http\Controllers\Helpers\CraydelHelperFunctions;
use App\Http\Controllers\Helpers\LanguageTranslationHelper;
use App\Http\Controllers\Traits\CanPaginate;
use App\Models\Cluster;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;

class CreateClusterCommandController
{


    protected ClusterController $clusterController;

    public function __construct(ClusterController $clusterController)
    {
        $this->clusterController = $clusterController;
    }

    /**
     * List Courses
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function create(Request $request): JsonResponse
    {
        try {
            $cluster_name =  CraydelHelperFunctions::toCleanString($request->input('cluster_name'));
            $country_id = CraydelHelperFunctions::toCleanString($request->input('country_id'));
            if (empty($cluster_name)) {
                return $this->clusterController->respondInJSON(new CraydelJSONResponseType(
                    false,
                    LanguageTranslationHelper::translate('clusters.errors.missing_cluster_name')
                ));
            }
            if (empty($country_id)) {
                return $this->clusterController->respondInJSON(new CraydelJSONResponseType(
                    false,
                    LanguageTranslationHelper::translate('clusters.errors.missing_country_id')
                ));
            }
            if (DB::table((new Cluster())->getTable())->where('cluster_name', $request->input('cluster_name'))->exists()) {
                return $this->clusterController->respondInJSON(new CraydelJSONResponseType(
                    false,
                    LanguageTranslationHelper::translate('clusters.errors.duplicate_cluster_name')
                ));
            }
            $affected = DB::table('clusters')->insert([
                ['cluster_name' => $cluster_name, 'country_id' => $country_id,
                    'created_at' => Carbon::now()->toDateTimeString()]
            ]);

            if ($affected) {
                return $this->clusterController->respondInJSON(new CraydelJSONResponseType(
                    true,
                    LanguageTranslationHelper::translate('clusters.success.created')
                ));
            }

        } catch (\Exception $exception) {
            $this->clusterController->logException($exception);
            return $this->clusterController->respondInJSON((new CraydelJSONResponseType(
                false,
                LanguageTranslationHelper::translate('clusters.errors.list_general_errors')
            )));
        }
    }
}
