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

class DeleteCoursePathwaysCommandController
{


    protected CoursesPathwaysController $coursesPathwaysController;


    public function __construct(CoursesPathwaysController $coursesPathwaysController)
    {
        $this->coursesPathwaysController = $coursesPathwaysController;
    }

    public function delete(?string $course_pathway_id): JsonResponse
    {
        try {
            $pathway = CoursesPathway::find($course_pathway_id);
            $pathway->delete();

            return $this->coursesPathwaysController->respondInJSON(new CraydelJSONResponseType(
                true,
                LanguageTranslationHelper::translate('pathways.success.is_deleted')
            ));

        } catch (\Exception $exception) {
            $this->coursesPathwaysController->logException($exception);
            return $this->coursesPathwaysController->respondInJSON((new CraydelJSONResponseType(
                false,
                LanguageTranslationHelper::translate('pathways.errors.is_deleted')
            )));
        }
    }
}
