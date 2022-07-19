<?php

namespace App\Http\Controllers\DisplaySubjects\Commands;

use App\Http\Controllers\DisplaySubjects\DisplaySubjectsController;
use App\Http\Controllers\CraydelTypes\CraydelJSONResponseType;
use App\Http\Controllers\Helpers\CraydelHelperFunctions;
use App\Http\Controllers\Helpers\LanguageTranslationHelper;
use App\Http\Controllers\Traits\CanPaginate;
use App\Models\DisplaySubjects;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;

class CreateDisplaySubjectsCommandController
{

    /**
     * @var DisplaySubjectsController
     */
    protected DisplaySubjectsController $displaySubjectsController;

    /**
     * Constructor
     * @param DisplaySubjectsController $displaySubjectsController
     */
    public function __construct(DisplaySubjectsController $displaySubjectsController)
    {
        $this->displaySubjectsController = $displaySubjectsController;
    }

    /**
     * List Courses
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function create(Request $request): JsonResponse
    {
        try {
            $education_type_id = CraydelHelperFunctions::toCleanString($request->input('education_type_id'));
            $country_id = CraydelHelperFunctions::toCleanString($request->input('country_id'));
            $academic_disciplines_id = CraydelHelperFunctions::toCleanString($request->input('academic_disciplines_id'));
            $subject_title = CraydelHelperFunctions::toCleanString($request->input('subject_title'));
            $subject_title_description = CraydelHelperFunctions::toCleanString($request->input('subject_title_description'));
            $display_order = CraydelHelperFunctions::toCleanString($request->input('display_order'));

            if (empty($education_type_id)) {
                return $this->displaySubjectsController->respondInJSON(new CraydelJSONResponseType(
                    false,
                    LanguageTranslationHelper::translate('display_subjects.errors.missing_education_type_id')
                ));
            }
            if (empty($country_id)) {
                return $this->displaySubjectsController->respondInJSON(new CraydelJSONResponseType(
                    false,
                    LanguageTranslationHelper::translate('display_subjects.errors.missing_country_id')
                ));
            }
            if (empty($academic_disciplines_id)) {
                return $this->displaySubjectsController->respondInJSON(new CraydelJSONResponseType(
                    false,
                    LanguageTranslationHelper::translate('display_subjects.errors.missing_academic_disciplines_id')
                ));
            }
            if (empty($subject_title)) {
                return $this->displaySubjectsController->respondInJSON(new CraydelJSONResponseType(
                    false,
                    LanguageTranslationHelper::translate('display_subjects.errors.missing_subject_title')
                ));
            }
            if (empty($subject_title_description)) {
                return $this->displaySubjectsController->respondInJSON(new CraydelJSONResponseType(
                    false,
                    LanguageTranslationHelper::translate('display_subjects.errors.missing_subject_title_description')
                ));
            }
            if (empty($display_order)) {
                return $this->displaySubjectsController->respondInJSON(new CraydelJSONResponseType(
                    false,
                    LanguageTranslationHelper::translate('display_subjects.errors.missing_display_order')
                ));
            }
            $affected = DB::table('display_subjects')->insert([
                ['education_type_id' => $education_type_id,
                    'country_id' => $country_id,
                    'academic_disciplines_id' => $academic_disciplines_id,
                    'subject_title' => $subject_title,
                    'subject_title_description' => $subject_title_description,
                    'display_order' => $display_order,
                    'created_at' => Carbon::now()->toDateTimeString()]
            ]);


                return $this->displaySubjectsController->respondInJSON(new CraydelJSONResponseType(
                    true,
                    LanguageTranslationHelper::translate('display_subjects.success.created')
                ));


        } catch (\Exception $exception) {
            $this->displaySubjectsController->logException($exception);
            return $this->displaySubjectsController->respondInJSON((new CraydelJSONResponseType(
                false,
                LanguageTranslationHelper::translate('display_subjects.errors.list_general_errors')
            )));
        }
    }
}
