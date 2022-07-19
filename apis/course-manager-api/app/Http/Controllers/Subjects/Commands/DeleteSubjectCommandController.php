<?php

namespace App\Http\Controllers\Subjects\Commands;

use App\Http\Controllers\Subjects\SubjectController;
use App\Http\Controllers\CraydelTypes\CraydelJSONResponseType;
use App\Http\Controllers\Helpers\CraydelHelperFunctions;
use App\Http\Controllers\Helpers\LanguageTranslationHelper;
use App\Http\Controllers\Traits\CanPaginate;
use App\Models\ClusterSubject;
use App\Models\Subject;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;

class DeleteSubjectCommandController
{

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

    public function delete(?string $subject_id): JsonResponse
    {
        try {

            $count = ClusterSubject::where('subject_id','=',$subject_id)->count();
            if (!empty($count)) {
                return $this->subjectController->respondInJSON((new CraydelJSONResponseType(
                    false,
                    LanguageTranslationHelper::translate('clusters.errors.cant_deleted')
                )));
            }
            $subject = Subject::find($subject_id);
            $subject->delete();

            return $this->subjectController->respondInJSON(new CraydelJSONResponseType(
                true,
                LanguageTranslationHelper::translate('subjects.success.is_deleted')
            ));

        } catch (\Exception $exception) {
            $this->subjectController->logException($exception);
            return $this->subjectController->respondInJSON((new CraydelJSONResponseType(
                false,
                LanguageTranslationHelper::translate('subjects.errors.is_deleted')
            )));
        }
    }
}
