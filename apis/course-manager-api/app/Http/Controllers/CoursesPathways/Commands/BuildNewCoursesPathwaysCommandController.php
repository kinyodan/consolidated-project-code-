<?php

namespace App\Http\Controllers\CoursesPathways\Commands;


use App\Http\Controllers\CoursesPathways\CoursesPathwaysController;
use App\Http\Controllers\CraydelTypes\CraydelJSONResponseType;
use App\Http\Controllers\Helpers\AcademicDisciplineHelper;
use App\Http\Controllers\Helpers\ClusterSubjectHelper;
use App\Http\Controllers\Helpers\LanguageTranslationHelper;
use App\Http\Controllers\Helpers\LearningModeHelper;
use Illuminate\Http\JsonResponse;
use phpDocumentor\Reflection\Types\Collection;

class BuildNewCoursesPathwaysCommandController
{
    protected CoursesPathwaysController $coursesPathwaysController;


    public function __construct(CoursesPathwaysController $coursesPathwaysController)
    {
        $this->coursesPathwaysController = $coursesPathwaysController;
    }


    /**
     * Build a new cluster subject request
     */
    public function build(): JsonResponse
    {
        return $this->coursesPathwaysController->respondInJSON((new CraydelJSONResponseType(
            true,
            LanguageTranslationHelper::translate('clusters.success.built'), array(
                'disciplines' =>AcademicDisciplineHelper::disciplines(),
                'pathways' => ClusterSubjectHelper::pathways(),


            )
        )));
    }
}
