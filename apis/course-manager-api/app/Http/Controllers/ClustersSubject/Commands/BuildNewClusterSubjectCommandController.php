<?php

namespace App\Http\Controllers\ClustersSubject\Commands;

use App\Http\Controllers\ClustersSubject\ClusterSubjectController;
use App\Http\Controllers\Courses\Commands\ValidateCourseDetails;
use App\Http\Controllers\CraydelTypes\CraydelJSONResponseType;
use App\Http\Controllers\Helpers\AcademicDisciplineHelper;
use App\Http\Controllers\Helpers\ClusterSubjectHelper;
use App\Http\Controllers\Helpers\CourseTypesHelper;
use App\Http\Controllers\Helpers\InstitutionsHelper;
use App\Http\Controllers\Helpers\LanguageTranslationHelper;
use App\Http\Controllers\Helpers\LearningModeHelper;
use Illuminate\Http\JsonResponse;
use phpDocumentor\Reflection\Types\Collection;

class BuildNewClusterSubjectCommandController
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
     * Build a new cluster subject request
     */
    public function build(): JsonResponse
    {
        return $this->clusterSubjectController->respondInJSON((new CraydelJSONResponseType(
            true,
            LanguageTranslationHelper::translate('clusters.success.built'), array(
                'subjects' => ClusterSubjectHelper::subjects(),
                'education_types' => ClusterSubjectHelper::educations(),
                'countries' => ClusterSubjectHelper::countries(),
                'clusters' => ClusterSubjectHelper::clusters(),
                'careers' => ClusterSubjectHelper::careers_pathways(),
                'courses' => ClusterSubjectHelper::courses()

            )
        )));
    }
}
