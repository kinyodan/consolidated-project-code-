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
use Illuminate\Support\Facades\Log;

class CreateSubjectsClustersCommandController
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
            $cluster_id = CraydelHelperFunctions::toCleanString($request->input('cluster_id'));
            $subject_id = CraydelHelperFunctions::toCleanString($request->input('subject_id'));
            $education_type_id = CraydelHelperFunctions::toCleanString($request->input('education_type_id'));
            if (empty($cluster_id)) {
                return $this->clusterSubjectController->respondInJSON(new CraydelJSONResponseType(
                    false,
                    LanguageTranslationHelper::translate('clusters.errors.missing_cluster_id')
                ));
            }
            if (empty($subject_id)) {
                return $this->clusterSubjectController->respondInJSON(new CraydelJSONResponseType(
                    false,
                    LanguageTranslationHelper::translate('clusters.errors.missing_subject_id')
                ));
            }
            if (empty($education_type_id)) {
                return $this->clusterSubjectController->respondInJSON(new CraydelJSONResponseType(
                    false,
                    LanguageTranslationHelper::translate('clusters.errors.missing_education_type_id')
                ));
            }

            $subjects = json_decode($subject_id);
            foreach ($subjects as $subject) {
                DB::table('subjects_clusters')->insertOrIgnore([
                    ['cluster_id' => $cluster_id,
                        'subject_id' => $subject,
                        'education_type_id' => $education_type_id,
                        'created_at' => Carbon::now()->toDateTimeString()]
                ]);
            }


            return $this->clusterSubjectController->respondInJSON(new CraydelJSONResponseType(
                true,
                LanguageTranslationHelper::translate('clusters.success.created')
            ));


        } catch (\Exception $exception) {
            $this->clusterSubjectController->logException($exception);
            return $this->clusterSubjectController->respondInJSON((new CraydelJSONResponseType(
                false,
                LanguageTranslationHelper::translate('clusters.errors.list_general_errors')
            )));
        }
    }
}
