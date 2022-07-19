<?php

namespace App\Http\Controllers\CoursesPathways\Commands;

use App\Http\Controllers\CoursesPathways\CoursesPathwaysController;
use App\Http\Controllers\CraydelTypes\CraydelJSONResponseType;
use App\Http\Controllers\Helpers\CraydelHelperFunctions;
use App\Http\Controllers\Helpers\LanguageTranslationHelper;
use App\Http\Controllers\Traits\CanPaginate;
use App\Models\CoursesPathway;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;

class UpdateCoursesPathwaysCommandController
{


    protected CoursesPathwaysController $coursesPathwaysController;


    public function __construct(CoursesPathwaysController $coursesPathwaysController)
    {
        $this->coursesPathwaysController = $coursesPathwaysController;
    }

    public function update(Request $request, ?string $course_pathway_id): JsonResponse
    {
        try {
            $career_pathways_id = CraydelHelperFunctions::toCleanString($request->input('career_pathways_id'));
            $academic_disciplines_id = CraydelHelperFunctions::toCleanString($request->input('academic_disciplines_id'));
            if (empty($career_pathways_id)) {
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

            DB::transaction(function () use ($career_pathways_id, $academic_disciplines_id,$course_pathway_id) {
                {
                    DB::table((new CoursesPathway())->getTable())->where('id', $course_pathway_id)->update([
                        'academic_disciplines_id' => $academic_disciplines_id,
                        'career_pathways_id' => $career_pathways_id,
                        'updated_at' => Carbon::now()->toDateTimeString(),
                    ]);
                }
            });

            return $this->coursesPathwaysController->respondInJSON(new CraydelJSONResponseType(
                true,
                LanguageTranslationHelper::translate('pathways.success.is_updated')
            ));

        } catch (\Exception $exception) {
            $this->coursesPathwaysController->logException($exception);
            return $this->coursesPathwaysController->respondInJSON((new CraydelJSONResponseType(
                false,
                LanguageTranslationHelper::translate('pathways.errors.is_updated')
            )));
        }
    }
}
