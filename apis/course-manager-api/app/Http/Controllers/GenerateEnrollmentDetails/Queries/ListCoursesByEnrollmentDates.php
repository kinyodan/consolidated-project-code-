<?php

namespace App\Http\Controllers\GenerateEnrollmentDetails\Queries;

use App\Http\Controllers\Courses\CourseController;
use App\Http\Controllers\CraydelTypes\CraydelJSONResponseType;
use App\Http\Controllers\GenerateEnrollmentDetails\GenerateEnrollmentDetailsController;
use App\Http\Controllers\Helpers\CraydelHelperFunctions;
use App\Http\Controllers\Helpers\LanguageTranslationHelper;
use App\Http\Controllers\Traits\CanPaginate;
use App\Models\CourseSummary;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;

class ListCoursesByEnrollmentDates
{
    protected GenerateEnrollmentDetailsController $generateEnrollmentDetailsController;

    public function __construct(GenerateEnrollmentDetailsController $generateEnrollmentDetailsController)
    {
        $this->generateEnrollmentDetailsController = $generateEnrollmentDetailsController;
    }

    /**
     * List Courses
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function get(Request $request): JsonResponse
    {

        try {
            $enrollment_date = CraydelHelperFunctions::toCleanString($request->input('enrollment_date'));
            return $this->generateEnrollmentDetailsController->respondInJSON((new CraydelJSONResponseType(
                true,
                LanguageTranslationHelper::translate('courses.success.listed'),
                CourseSummary::where('enrollment_dates', '=', $enrollment_date)
                    ->orderBy('id', 'ASC')
                    ->get()
            )));

        } catch (\Exception $exception) {
            $this->generateEnrollmentDetailsController->logException($exception);
            return $this->generateEnrollmentDetailsController->respondInJSON((new CraydelJSONResponseType(
                false,
                LanguageTranslationHelper::translate('courses.errors.list_general_errors')
            )));
        }
    }
}
