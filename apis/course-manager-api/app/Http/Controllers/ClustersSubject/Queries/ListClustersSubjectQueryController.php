<?php

namespace App\Http\Controllers\ClustersSubject\Queries;

use App\Http\Controllers\ClustersSubject\ClusterSubjectController;
use App\Http\Controllers\CraydelTypes\CraydelJSONResponseType;
use App\Http\Controllers\Helpers\CraydelHelperFunctions;
use App\Http\Controllers\Helpers\LanguageTranslationHelper;
use App\Http\Controllers\Traits\CanPaginate;
use App\Models\Cluster;
use App\Models\ClusterSubject;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;

class ListClustersSubjectQueryController
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
            $clusters = ClusterSubject::where('cluster_subjects.is_published', '=', 1)
                ->join('clusters','clusters.id','=','cluster_subjects.cluster_id')
                ->join('countries','countries.id','=','cluster_subjects.country_id')
                ->join('education_types','education_types.id','=','cluster_subjects.education_type_id')
                ->join('subjects','subjects.id','=','cluster_subjects.subject_id')
                ->select('clusters.id as cluster_id','clusters.cluster_name as cluster_name','countries.id as country_id','countries.name as country_name','education_types.id as education_types_id','education_types.education_type_name','subjects.id as subjects_id','subjects.subject_name');

            $currentPage = !empty($currentPage) ? $currentPage : 1;
            $this->currentPaginationPage = $currentPage;
            $this->totalNumberOfEntities = $clusters->count('cluster_subjects.id');
            $this->itemsPerPage = config('craydle.items_per_page', 20);
            Paginator::currentPageResolver(function () use ($currentPage) {
                return $currentPage;
            });

            $clusters = $clusters
                ->orderBy('cluster_subjects.id', 'DESC')
                ->simplePaginate($this->itemsPerPage);


            return $this->clusterSubjectController->respondInJSON((new CraydelJSONResponseType(
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
            $this->clusterSubjectController->logException($exception);
            return $this->clusterSubjectController->respondInJSON((new CraydelJSONResponseType(
                false,
                LanguageTranslationHelper::translate('clusters.errors.list_general_errors')
            )));
        }
    }


}


