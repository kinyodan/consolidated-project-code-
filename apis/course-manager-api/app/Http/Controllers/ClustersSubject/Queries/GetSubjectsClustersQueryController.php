<?php


namespace App\Http\Controllers\ClustersSubject\Queries;

use App\Http\Controllers\ClustersSubject\ClusterSubjectController;
use App\Http\Controllers\CraydelTypes\CraydelJSONResponseType;
use App\Http\Controllers\Helpers\CraydelHelperFunctions;
use App\Http\Controllers\Helpers\LanguageTranslationHelper;
use App\Http\Controllers\Traits\CanPaginate;
use App\Models\ClusterSubject;
use App\Models\SubjectCluster;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;

class GetSubjectsClustersQueryController
{
    use CanPaginate;

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


    public function get(Request $request): JsonResponse
    {

        try {
            $currentPage = $request->input('page');
            $clusters = SubjectCluster::join('clusters', 'clusters.id', '=', 'subjects_clusters.cluster_id')
                ->join('subjects', 'subjects.id', '=', 'subjects_clusters.subject_id')
                ->join('education_types', 'education_types.id', '=', 'subjects_clusters.education_type_id')
                ->select('clusters.cluster_name as cluster','clusters.id as cluster_id', 'education_types.education_type_name as education_type','subjects.subject_name as subject');

            $currentPage = !empty($currentPage) ? $currentPage : 1;
            $this->currentPaginationPage = $currentPage;
            $this->totalNumberOfEntities = $clusters->count('subjects_clusters.id');
            $this->itemsPerPage = config('craydle.items_per_page', 20);
            Paginator::currentPageResolver(function () use ($currentPage) {
                return $currentPage;
            });
            $clusters = $clusters
                ->orderBy('subjects_clusters.id', 'DESC')
                ->simplePaginate($this->itemsPerPage);
            return $this->clusterSubjectController->respondInJSON((new CraydelJSONResponseType(
                true,
                LanguageTranslationHelper::translate('clusters.success.select'),
                call_user_func(function () use ($clusters) {
                    return [
                        'items' => call_user_func(function () use ($clusters) {
                            $items = is_callable(array($clusters, 'items')) ? $clusters->items() : array();

                            $_list = [];

                            foreach ($items as $item) {
                                $_list[] = [
                                    'cluster_name' => $item->cluster,
                                    'subjects'=> $item
                                ];
                            }

                            $groups = collect($_list)->groupBy('cluster_name')->toArray();
                            $subs = [];

                            foreach ($groups as $group){
                                $subs [] = [
                                    'cluster_name' => $group[0]['cluster_name'],
                                    'subjects' => call_user_func(function () use($group){
                                        $clusters = [];

                                        foreach ($group as $item){
                                            $clusters [] = $item['subjects'];
                                        }

                                        return $clusters;
                                    })
                                ];
                            }

                            return $subs;
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
            $this->clusterSubjectController->logException($exception);
            return $this->clusterSubjectController->respondInJSON((new CraydelJSONResponseType(
                false,
                LanguageTranslationHelper::translate('clusters.errors.is_selected')
            )));
        }
    }
}
