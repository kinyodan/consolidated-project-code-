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

class UpdateClusterCommandController
{


    protected ClusterController  $clusterController;


    /**
     * @param ClusterController $clusterController
     */
    public function __construct(ClusterController $clusterController)
    {
        $this->clusterController = $clusterController;
    }

    /**
     * List Courses
     *
     * @param Request $request
     * @param string|null $cluster_id
     * @return JsonResponse
     */
    public function update(Request $request, ?string $cluster_id): JsonResponse
    {
        try {
            $cluster_name = CraydelHelperFunctions::toCleanString($request->input('cluster_name'));
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

            DB::transaction(function () use ($cluster_name, $cluster_id,$country_id) {
                {
                    DB::table((new Cluster())->getTable())->where('id', $cluster_id)->update([
                        'cluster_name' => $cluster_name,
                        'country_id' => $country_id,
                        'updated_at' => Carbon::now()->toDateTimeString(),
                    ]);
                }
            });

            return $this->clusterController->respondInJSON(new CraydelJSONResponseType(
                true,
                LanguageTranslationHelper::translate('clusters.success.is_updated')
            ));

        } catch (\Exception $exception) {
            $this->clusterController->logException($exception);
            return $this->clusterController->respondInJSON((new CraydelJSONResponseType(
                false,
                LanguageTranslationHelper::translate('clusters.errors.is_updated')
            )));
        }
    }
}
