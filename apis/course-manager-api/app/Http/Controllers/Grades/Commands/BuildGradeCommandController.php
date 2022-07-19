<?php

namespace App\Http\Controllers\Grades\Commands;

use App\Http\Controllers\ClustersSubject\ClusterSubjectController;
use App\Http\Controllers\Courses\Commands\ValidateCourseDetails;
use App\Http\Controllers\CraydelTypes\CraydelJSONResponseType;
use App\Http\Controllers\Grades\GradeController;
use App\Http\Controllers\Helpers\AcademicDisciplineHelper;
use App\Http\Controllers\Helpers\ClusterSubjectHelper;
use App\Http\Controllers\Helpers\CourseTypesHelper;
use App\Http\Controllers\Helpers\InstitutionsHelper;
use App\Http\Controllers\Helpers\LanguageTranslationHelper;
use App\Http\Controllers\Helpers\LearningModeHelper;
use Illuminate\Http\JsonResponse;
use phpDocumentor\Reflection\Types\Collection;

class BuildGradeCommandController
{
    protected GradeController $gradeController;


    public function __construct(GradeController $gradeController)
    {
        $this->gradeController = $gradeController;
    }


    /**
     * Build a new cluster subject request
     */
    public function build(): JsonResponse
    {
        return $this->gradeController->respondInJSON((new CraydelJSONResponseType(
            true,
            LanguageTranslationHelper::translate('grades.success.built'), array(
                'subjects' => ClusterSubjectHelper::subjects(),
                'education_types' => ClusterSubjectHelper::educations(),
                'countries' => ClusterSubjectHelper::countries(),
                'clusters' => ClusterSubjectHelper::clusters(),


            )
        )));
    }
}
