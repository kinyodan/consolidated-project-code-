<?php

namespace App\Http\Controllers\Grades\Commands;

use App\Http\Controllers\CraydelTypes\CraydelJSONResponseType;
use App\Http\Controllers\Grades\GradeController;
use App\Http\Controllers\Helpers\CraydelHelperFunctions;
use App\Http\Controllers\Helpers\LanguageTranslationHelper;
use App\Http\Controllers\Traits\CanPaginate;
use App\Models\Grade;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DeleteGradeCommandController
{


    protected GradeController $gradeController;


    public function __construct(GradeController $gradeController)
    {
        $this->gradeController = $gradeController;
    }

    public function delete(?string $grade_id): JsonResponse
    {
        try {

            $grade = Grade::find($grade_id);
            $grade->delete();

            return $this->gradeController->respondInJSON(new CraydelJSONResponseType(
                true,
                LanguageTranslationHelper::translate('grades.success.is_deleted')
            ));

        } catch (\Exception $exception) {
            $this->gradeController->logException($exception);
            return $this->gradeController->respondInJSON((new CraydelJSONResponseType(
                false,
                LanguageTranslationHelper::translate('grades.errors.is_deleted')
            )));
        }
    }
}
