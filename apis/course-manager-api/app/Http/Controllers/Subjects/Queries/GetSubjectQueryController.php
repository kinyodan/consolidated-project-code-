<?php

namespace App\Http\Controllers\Subjects\Queries;

use App\Http\Controllers\Subjects\SubjectController;
use App\Http\Controllers\CraydelTypes\CraydelJSONResponseType;
use App\Http\Controllers\Helpers\CraydelHelperFunctions;
use App\Http\Controllers\Helpers\LanguageTranslationHelper;
use App\Http\Controllers\Traits\CanPaginate;
use App\Models\Subject;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;

class GetSubjectQueryController
{
    use CanPaginate;

    /**
     * @var SubjectController
     */
    protected SubjectController $subjectController;

    /**
     * Constructor
     * @param SubjectController $subjectController
     */
    public function __construct(SubjectController $subjectController)
    {
        $this->subjectController = $subjectController;
    }

    /**
     * List Courses
     *
     * @param string|null $subject_id
     * @return JsonResponse
     */
    public function get(?string $subject_id): JsonResponse
    {
        try {
            $subject=Subject::findOrFail($subject_id);
            return $this->subjectController->respondInJSON(new CraydelJSONResponseType(
                true,
                LanguageTranslationHelper::translate('subjects.success.is_selected'),
                $subject
            ));

        } catch (\Exception $exception) {
            $this->subjectController->logException($exception);
            return $this->subjectController->respondInJSON((new CraydelJSONResponseType(
                false,
                LanguageTranslationHelper::translate('subjects.errors.is_selected')
            )));
        }
    }
}
