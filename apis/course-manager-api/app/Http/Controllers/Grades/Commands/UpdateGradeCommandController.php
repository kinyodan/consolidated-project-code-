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

class UpdateGradeCommandController
{


    protected GradeController $gradeController;


    public function __construct(GradeController $gradeController)
    {
        $this->gradeController = $gradeController;
    }


    public function update(Request $request, $grade_id): JsonResponse
    {
        try {
            $country_id = CraydelHelperFunctions::toCleanString($request->input('country_id'));
            $min = CraydelHelperFunctions::toCleanString($request->input('min'));
            $max = CraydelHelperFunctions::toCleanString($request->input('max'));
            $grade_equivalent = CraydelHelperFunctions::toCleanString($request->input('grade_equivalent'));

            if (empty($country_id)) {
                return $this->gradeController->respondInJSON(new CraydelJSONResponseType(
                    false,
                    LanguageTranslationHelper::translate('grades.errors.missing_country_id')
                ));
            }
            if (empty($min)) {
                return $this->gradeController->respondInJSON(new CraydelJSONResponseType(
                    false,
                    LanguageTranslationHelper::translate('grades.errors.missing_min_value')
                ));
            }
            if (empty($max)) {
                return $this->gradeController->respondInJSON(new CraydelJSONResponseType(
                    false,
                    LanguageTranslationHelper::translate('grades.errors.missing_max_value')
                ));
            }
            if (empty($grade_equivalent)) {
                return $this->gradeController->respondInJSON(new CraydelJSONResponseType(
                    false,
                    LanguageTranslationHelper::translate('grades.errors.missing_grade_equivalent')
                ));
            }

            DB::table((new Grade())->getTable())->where('id', trim($grade_id))->update([
                'country_id' => $country_id,
                'min' => $min,
                'max' => $max,
                'grade_equivalent' => $grade_equivalent,
                'updated_at' => Carbon::now()->toDateTimeString(),
            ]);

            return $this->gradeController->respondInJSON(new CraydelJSONResponseType(
                true,
                LanguageTranslationHelper::translate('grades.success.is_updated')
            ));

        } catch (\Exception $exception) {
            $this->gradeController->logException($exception);
            return $this->gradeController->respondInJSON((new CraydelJSONResponseType(
                false,
                LanguageTranslationHelper::translate('grades.errors.created')
            )));
        }


    }

}
