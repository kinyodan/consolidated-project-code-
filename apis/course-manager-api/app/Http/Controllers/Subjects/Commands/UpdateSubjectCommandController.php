<?php

namespace App\Http\Controllers\Subjects\Commands;

use App\Http\Controllers\Subjects\SubjectController;
use App\Http\Controllers\CraydelTypes\CraydelJSONResponseType;
use App\Http\Controllers\Helpers\CraydelHelperFunctions;
use App\Http\Controllers\Helpers\LanguageTranslationHelper;
use App\Http\Controllers\Traits\CanPaginate;
use App\Models\Subject;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;

class UpdateSubjectCommandController
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

    /**
     * List Courses
     *
     * @param Request $request
     * @param string|null $subject_id
     * @return JsonResponse
     */
    public function update(Request $request, ?string $subject_id): JsonResponse
    {
        try {
            $subject_name = CraydelHelperFunctions::toCleanString($request->input('subject_name'));
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

            DB::transaction(function () use ($subject_id, $subject_name,$country_id) {
                {
                    DB::table((new Subject())->getTable())->where('id', $subject_id)->update([
                        'subject_name' => $subject_name,
                        'country_id' => $country_id,
                        'updated_at' => Carbon::now()->toDateTimeString(),
                    ]);
                }
            });

            return $this->subjectController->respondInJSON(new CraydelJSONResponseType(
                true,
                LanguageTranslationHelper::translate('subjects.success.is_updated')
            ));

        } catch (\Exception $exception) {
            $this->subjectController->logException($exception);
            return $this->subjectController->respondInJSON((new CraydelJSONResponseType(
                false,
                LanguageTranslationHelper::translate('subjects.errors.is_updated')
            )));
        }
    }
}
