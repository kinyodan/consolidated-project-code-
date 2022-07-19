<?php

namespace App\Http\Controllers\CoursesPathways\Commands;


use App\Http\Controllers\CoursesPathways\CoursesPathwaysController;
use App\Http\Controllers\CraydelTypes\CraydelJSONResponseType;
use App\Http\Controllers\Helpers\AcademicDisciplineHelper;
use App\Http\Controllers\Helpers\ClusterSubjectHelper;
use App\Http\Controllers\Helpers\CraydelHelperFunctions;
use App\Http\Controllers\Helpers\LanguageTranslationHelper;
use App\Http\Controllers\Helpers\LearningModeHelper;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use phpDocumentor\Reflection\Types\Collection;

class CreateCoursesPathwayCommandController
{
    protected CoursesPathwaysController $coursesPathwaysController;


    public function __construct(CoursesPathwaysController $coursesPathwaysController)
    {
        $this->coursesPathwaysController = $coursesPathwaysController;
    }


    /**
     * Build a new cluster subject request
     */
    public function create(Request $request): JsonResponse
    {
        try {
            $career_pathway_id = CraydelHelperFunctions::toCleanString($request->input('career_pathways_id'));
            $academic_disciplines_id = CraydelHelperFunctions::toCleanString($request->input('academic_disciplines_id'));
            $academic_disciplines_id = json_decode($academic_disciplines_id);




            if (empty($career_pathway_id)) {
                return $this->coursesPathwaysController->respondInJSON(new CraydelJSONResponseType(
                    false,
                    LanguageTranslationHelper::translate('pathways.errors.missing_career_pathways_id')
                ));
            }
            if (empty($academic_disciplines_id)) {
                return $this->coursesPathwaysController->respondInJSON(new CraydelJSONResponseType(
                    false,
                    LanguageTranslationHelper::translate('pathways.errors.missing_academic_disciplines_id')
                ));
            }

            foreach ($academic_disciplines_id as $disciplines_id) {

                DB::table('courses_careers_pathways')->insertOrIgnore([
                    ['academic_disciplines_id' => $disciplines_id,
                        'career_pathways_id' => $career_pathway_id,
                        'created_at' => Carbon::now()->toDateTimeString()]
                ]);

            }


            return $this->coursesPathwaysController->respondInJSON(new CraydelJSONResponseType(
                true,
                LanguageTranslationHelper::translate('pathways.success.created')
            ));


        } catch (\Exception $exception) {
            $this->coursesPathwaysController->logException($exception);
            return $this->coursesPathwaysController->respondInJSON((new CraydelJSONResponseType(
                false,
                LanguageTranslationHelper::translate('pathways.errors.list_general_errors')
            )));
        }
    }
}
