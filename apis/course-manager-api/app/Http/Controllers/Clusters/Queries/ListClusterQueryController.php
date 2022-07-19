<?php

namespace App\Http\Controllers\Clusters\Queries;

use App\Http\Controllers\Clusters\ClusterController;
use App\Http\Controllers\CraydelTypes\CraydelJSONResponseType;
use App\Http\Controllers\Helpers\CraydelHelperFunctions;
use App\Http\Controllers\Helpers\LanguageTranslationHelper;
use App\Http\Controllers\Traits\CanPaginate;
use App\Models\Cluster;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;

class ListClusterQueryController
{
    use CanPaginate;

    protected ClusterController $clusterController;


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
     *
     * @return JsonResponse
     */
    public function get(Request $request): JsonResponse
    {
        try {
            $currentPage = $request->input('page');
            $country_id = CraydelHelperFunctions::toCleanString($request->input('country_id'));
            $search_term = CraydelHelperFunctions::toCleanString($request->input('search_term'));
            $clusters = Cluster::where('is_published', '=', 1)
                ->join('countries', 'countries.id', '=', 'clusters.country_id')
                ->select('clusters.cluster_name','clusters.id as clusters_id', 'countries.name as country_name','countries.id as country_id');
            if (!empty($country_id)) {
                $clusters = $clusters->where('country_id', $country_id);
            }
            if (!empty($search_term)) {
                $clusters = $clusters->where('subject_name', 'LIKE', '%' . $search_term . '%');
            }
            $currentPage = !empty($currentPage) ? $currentPage : 1;
            $this->currentPaginationPage = $currentPage;
            $this->totalNumberOfEntities = $clusters->count('clusters.id');
            $this->itemsPerPage = config('craydle.items_per_page', 20);
            Paginator::currentPageResolver(function () use ($currentPage) {
                return $currentPage;
            });

            $clusters = $clusters
                ->orderBy('clusters.id', 'DESC')
                ->simplePaginate($this->itemsPerPage);

            return $this->clusterController->respondInJSON((new CraydelJSONResponseType(
                true,
                LanguageTranslationHelper::translate('clusters.success.listed'),
                call_user_func(function () use ($clusters) {
                    return [
                        'items' => call_user_func(function () use ($clusters) {
                            $items = is_callable(array($clusters, 'items')) ? $clusters->items() : array();

                            $_list = [];

                            foreach ($items as $item) {
                                $_list[] = [
                                    'country_name' => $item->country_name,
                                    'clusters'=> $item
                                ];
                            }

                            $groups = collect($_list)->groupBy('country_name')->toArray();
                            $countries = [];

                            foreach ($groups as $group){
                                $countries [] = [
                                    'country' => $group[0]['country_name'],
                                    'clusters' => call_user_func(function () use($group){
                                        $clusters = [];

                                        foreach ($group as $item){
                                            $clusters [] = $item['clusters'];
                                        }

                                        return $clusters;
                                    })
                                ];
                            }

                            return $countries;
                        }),

                        'items_per_page' => $this->itemsPerPage,
                        'current_page' => $this->currentPaginationPage,
                        'previous_page' => $this->previousPage(),
                        'next_page' => $this->nextPage(),
                        'number_of_pages' => $this->getTotalNumberOfPages(),
                        'items_count' => $this->totalNumberOfEntities
                    ];
                })
            )));
        } catch (\Exception $exception) {
            $this->clusterController->logException($exception);
            return $this->clusterController->respondInJSON((new CraydelJSONResponseType(
                false,
                LanguageTranslationHelper::translate('clusters.errors.list_general_errors')
            )));
        }
    }
}
