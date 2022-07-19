<?php


namespace App\Http\Controllers\Grades\Queries;

use App\Http\Controllers\Grades\GradeController;
use App\Http\Controllers\CraydelTypes\CraydelJSONResponseType;
use App\Http\Controllers\Helpers\CraydelHelperFunctions;
use App\Http\Controllers\Helpers\LanguageTranslationHelper;
use App\Http\Controllers\Traits\CanPaginate;
use App\Models\Grade;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class GetGradeQueryController
{
    use CanPaginate;

    protected GradeController $gradeController;


    public function __construct(GradeController $gradeController)
    {
        $this->gradeController = $gradeController;
    }


    public function get(?string $country_id): JsonResponse
    {

        try {

            $grades = DB::table((new Grade())->getTable())->select('id', 'min', 'max', 'grade_equivalent')->where(["country_id" => $country_id])->orderBy('max', 'DESC')->get();
            return $this->gradeController->respondInJSON((new CraydelJSONResponseType(
                true,
                LanguageTranslationHelper::translate('grades.success.select_country'),
                $grades
            )));
        } catch (\Exception $exception) {
            $this->gradeController->logException($exception);
            return $this->gradeController->respondInJSON((new CraydelJSONResponseType(
                false,
                LanguageTranslationHelper::translate('clusters.errors.is_selected')
            )));
        }
    }
}
