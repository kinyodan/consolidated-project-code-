<?php

namespace App\Http\Controllers\DisplaySubjects\Queries;

use App\Http\Controllers\DisplaySubjects\DisplaySubjectsController;
use App\Http\Controllers\CraydelTypes\CraydelJSONResponseType;
use App\Http\Controllers\Helpers\CraydelHelperFunctions;
use App\Http\Controllers\Helpers\LanguageTranslationHelper;
use App\Http\Controllers\Traits\CanPaginate;
use App\Models\DisplaySubjects;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Log;

class SelectDisplaySubjectController
{
    use CanPaginate;

    private DisplaySubjectsController $displaySubjectsController;

    /**
     * Constructor
     * @param DisplaySubjectsController $displaySubjectsController
     */
    public function __construct(DisplaySubjectsController $displaySubjectsController)
    {
        $this->displaySubjectsController = $displaySubjectsController;
    }

    public function get(Request $request, $display_subject_id): JsonResponse
    {
        try {

            return $this->displaySubjectsController->respondInJSON((new CraydelJSONResponseType(
                true,
                LanguageTranslationHelper::translate('display_subjects.success.is_selected'),
                DisplaySubjects::findOrFail($display_subject_id)
            )));


        } catch (\Exception $exception) {
            $this->displaySubjectsController->logException($exception);
            return $this->displaySubjectsController->respondInJSON((new CraydelJSONResponseType(
                false,
                LanguageTranslationHelper::translate('display_subjects.errors.list_general_errors')
            )));
        }
    }

}
