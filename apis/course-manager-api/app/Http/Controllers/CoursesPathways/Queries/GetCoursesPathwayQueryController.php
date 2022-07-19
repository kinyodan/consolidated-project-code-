<?php

namespace App\Http\Controllers\CoursesPathways\Queries;

use App\Http\Controllers\CoursesPathways\CoursesPathwaysController;
use App\Http\Controllers\CraydelTypes\CraydelJSONResponseType;
use App\Http\Controllers\Helpers\CraydelHelperFunctions;
use App\Http\Controllers\Helpers\LanguageTranslationHelper;
use App\Http\Controllers\Traits\CanPaginate;
use App\Models\CareerPathway;
use App\Models\CoursesPathway;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Log;

class GetCoursesPathwayQueryController
{
    use CanPaginate;

    protected CoursesPathwaysController $coursesPathwaysController;


    public function __construct(CoursesPathwaysController $coursesPathwaysController)
    {
        $this->coursesPathwaysController = $coursesPathwaysController;
    }

    public function get($course_pathway_id): JsonResponse
    {
        try {

            $pathway = CoursesPathway::where('courses_careers_pathways.id', '=', $course_pathway_id)
                ->join('academic_disciplines', 'academic_disciplines.id', '=', 'courses_careers_pathways.career_pathways_id')
                ->join('career_pathways', 'career_pathways.id', '=', 'courses_careers_pathways.career_pathways_id')
                ->select('academic_disciplines.id as academic_disciplines_id', 'academic_disciplines.discipline_name as discipline_name', 'career_pathways.id as career_pathways_id', 'career_pathways.career_pathway_name as career_pathway_name')
                ->get();

            Log::info($pathway);
            return $this->coursesPathwaysController->respondInJSON((new CraydelJSONResponseType(
                true,
                LanguageTranslationHelper::translate('pathways.success.select'),
                $pathway
            )));


        } catch (\Exception $exception) {
            $this->coursesPathwaysController->logException($exception);
            return $this->coursesPathwaysController->respondInJSON((new CraydelJSONResponseType(
                false,
                LanguageTranslationHelper::translate('pathways.errors.list_general_errors')
            )));
        }
    }


}
