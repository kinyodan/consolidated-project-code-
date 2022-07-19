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

class ListGradeQueryController
{
    use CanPaginate;

    protected GradeController $gradeController;


    public function __construct(GradeController $gradeController)
    {
        $this->gradeController = $gradeController;
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
            $currentPage = $request->input('page');
            $grades = Grade::where('grading_scale_system.is_published', '=', 1)
                ->join('countries', 'countries.id', '=', 'grading_scale_system.country_id')
                ->select('grading_scale_system.id as grade.id', 'grading_scale_system.min as minimum_grade', 'grading_scale_system.max as maximum_grade','grading_scale_system.grade_equivalent as grade_equivalent', 'countries.name as country');
            $currentPage = !empty($currentPage) ? $currentPage : 1;
            $this->currentPaginationPage = $currentPage;
            $this->totalNumberOfEntities = $grades->count('grading_scale_system.id');
            $this->itemsPerPage = config('craydle.items_per_page', 20);
            Paginator::currentPageResolver(function () use ($currentPage) {
                return $currentPage;
            });
            $grades = $grades
                ->orderBy('grading_scale_system.id', 'DESC')
                ->simplePaginate($this->itemsPerPage);
            return $this->gradeController->respondInJSON((new CraydelJSONResponseType(
                true,
                LanguageTranslationHelper::translate('grades.success.listed'),
                call_user_func(function () use ($grades) {
                    return [
                        'items' => call_user_func(function () use ($grades) {
                            $items = is_callable(array($grades, 'items')) ? $grades->items() : array();
                            $_list = [];
                            foreach ($items as $item) {
                                array_push($_list, $item->toArray());
                            }
                            return $_list;
                        }),
                        'items_per_page' => $this->itemsPerPage,
                        'current_page' => $this->currentPaginationPage,
                        'previous_page' => $this->previousPage(),
                        'next_page' => $this->nextPage(),
                        'number_of_pages' => $this->getTotalNumberOfPages(),
                        'items_count' => $this->totalNumberOfEntities
                    ];
                })
            )));
        } catch (\Exception $exception) {
            $this->gradeController->logException($exception);
            return $this->gradeController->respondInJSON((new CraydelJSONResponseType(
                false,
                LanguageTranslationHelper::translate('grades.errors.list_general_errors')
            )));
        }
    }
}
