<?php


namespace App\Http\Controllers\ClustersSubject\Queries;

use App\Http\Controllers\ClustersSubject\ClusterSubjectController;
use App\Http\Controllers\CraydelTypes\CraydelJSONResponseType;
use App\Http\Controllers\Helpers\CraydelHelperFunctions;
use App\Http\Controllers\Helpers\LanguageTranslationHelper;
use App\Http\Controllers\Traits\CanPaginate;
use App\Models\ClusterSubject;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;

class GetClustersSubjectQueryController
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


    public function get(Request $request, ?string $cluster_subject_id): JsonResponse
    {

        try {
            $currentPage = $request->input('page');
            $country_id = CraydelHelperFunctions::toCleanString($request->input('country_id'));
            $clusters = ClusterSubject::where('cluster_subjects.cluster_id', '=', $cluster_subject_id)
                ->join('clusters', 'clusters.id', '=', 'cluster_subjects.cluster_id')
                ->join('subjects', 'subjects.id', '=', 'cluster_subjects.subject_id')
                ->join('countries', 'countries.id', '=', 'cluster_subjects.country_id')
                ->join('education_types', 'education_types.id', '=', 'cluster_subjects.education_type_id')
                ->select('cluster_subjects.id as cluster_subjects_id','clusters.cluster_name as cluster','clusters.id as cluster_id', 'education_types.education_type_name as education_type','countries.id as country_id','countries.name as country', 'subjects.subject_name as subject','cluster_subjects.is_primary as is_primary');
            if (!empty($country_id)) {
                $clusters = $clusters->where('cluster_subjects.country_id', $country_id);
            }
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
                LanguageTranslationHelper::translate('clusters.success.select'),
                call_user_func(function () use ($clusters) {
                    return [
                        'items' => call_user_func(function () use ($clusters) {
                            $items = is_callable(array($clusters, 'items')) ? $clusters->items() : array();
                            $_list = [];
                            foreach ($items as $item) {
                                array_push($_list, $item->toArray());
                            }
                            return $_list;
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
