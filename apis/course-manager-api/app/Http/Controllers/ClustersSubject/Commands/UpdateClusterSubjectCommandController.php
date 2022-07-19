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

class UpdateClusterSubjectCommandController
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


    public function update(Request $request, $cluster_subject_id): JsonResponse
    {
        try {
            $cluster_id = CraydelHelperFunctions::toCleanString( $request->input('cluster_id'));
            $subject_id = CraydelHelperFunctions::toCleanString($request->input('subject_id'));
            $education_type_id = CraydelHelperFunctions::toCleanString($request->input('education_type_id'));
            $country_id = CraydelHelperFunctions::toCleanString($request->input('country_id'));
            $is_primary = CraydelHelperFunctions::toCleanString($request->input('is_primary'));
            $career_pathway_id = CraydelHelperFunctions::toCleanString($request->input('career_pathway_id'));
            $course_code =  CraydelHelperFunctions::toCleanString($request->input('course_code'));
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
            if (empty($country_id)) {
                return $this->clusterSubjectController->respondInJSON(new CraydelJSONResponseType(
                    false,
                    LanguageTranslationHelper::translate('clusters.errors.missing_country_id')
                ));
            }
            if (empty($is_primary)) {
                return $this->clusterSubjectController->respondInJSON(new CraydelJSONResponseType(
                    false,
                    LanguageTranslationHelper::translate('clusters.errors.missing_is_primary')
                ));
            }
            if (empty($career_pathway_id)) {
                return $this->clusterSubjectController->respondInJSON(new CraydelJSONResponseType(
                    false,
                    LanguageTranslationHelper::translate('clusters.errors.missing_is_career_pathway_id')
                ));
            }
            if (empty($course_code)) {
                return $this->clusterSubjectController->respondInJSON(new CraydelJSONResponseType(
                    false,
                    LanguageTranslationHelper::translate('clusters.errors.missing_is_course_code')
                ));
            }

            DB::transaction(function () use ($cluster_id, $subject_id, $education_type_id, $country_id, $is_primary, $cluster_subject_id, $career_pathway_id, $course_code) {
                {
                    DB::table((new ClusterSubject())->getTable())->where('id', $cluster_subject_id)->update([
                        'cluster_id' => $cluster_id,
                        'subject_id' => $subject_id,
                        'education_type_id' => $education_type_id,
                        'country_id' => $country_id,
                        'is_primary' => $is_primary,
                        'career_pathway_id' => $career_pathway_id,
                        'course_code' => $course_code,
                        'updated_at' => Carbon::now()->toDateTimeString(),
                    ]);
                }
            });


            return $this->clusterSubjectController->respondInJSON(new CraydelJSONResponseType(
                true,
                LanguageTranslationHelper::translate('clusters.success.is_updated')
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
