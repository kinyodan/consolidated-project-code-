<?php

namespace App\Http\Controllers\Subjects\Commands;

use App\Http\Controllers\Subjects\SubjectController;
use App\Http\Controllers\CraydelTypes\CraydelJSONResponseType;
use App\Http\Controllers\Helpers\CraydelHelperFunctions;
use App\Http\Controllers\Helpers\LanguageTranslationHelper;
use App\Http\Controllers\Traits\CanPaginate;
use App\Models\Subject;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;

class CreateSubjectCommandController
{

    /**
     * @var SubjectController
     */
    protected $subjectController;

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
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function create(Request $request): JsonResponse
    {
        try {
            $subject_name = CraydelHelperFunctions::toCleanString( $request->input('subject_name'));
            $country_id = CraydelHelperFunctions::toCleanString($request->input('country_id'));
            if (empty($subject_name)) {
                return $this->subjectController->respondInJSON(new CraydelJSONResponseType(
                    false,
                    LanguageTranslationHelper::translate('subjects.errors.missing_subject_name')
                ));
            }
            if (empty($country_id)) {
                return $this->subjectController->respondInJSON(new CraydelJSONResponseType(
                    false,
                    LanguageTranslationHelper::translate('subjects.errors.missing_country_id')
                ));
            }
            if (DB::table((new Subject())->getTable())->where('subject_name', $request->input('subject_name'))->exists()) {
                return $this->subjectController->respondInJSON(new CraydelJSONResponseType(
                    false,
                    LanguageTranslationHelper::translate('subjects.errors.duplicate_subject_name')
                ));
            }
            $affected = DB::table('subjects')->insert([
                ['subject_name' => $subject_name, 'country_id' => $country_id,
                    'created_at' => Carbon::now()->toDateTimeString()]
            ]);

            if ($affected) {
                return $this->subjectController->respondInJSON(new CraydelJSONResponseType(
                    true,
                    LanguageTranslationHelper::translate('subjects.success.created')
                ));
            }

        } catch (\Exception $exception) {
            $this->subjectController->logException($exception);
            return $this->subjectController->respondInJSON((new CraydelJSONResponseType(
                false,
                LanguageTranslationHelper::translate('subjects.errors.list_general_errors')
            )));
        }
    }
}
