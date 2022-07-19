<?php
namespace App\Http\Controllers\Courses\Queries;

use App\Http\Controllers\Courses\CourseController;
use App\Http\Controllers\CraydelTypes\CraydelJSONResponseType;
use App\Http\Controllers\Helpers\CraydelHelperFunctions;
use App\Http\Controllers\Helpers\LanguageTranslationHelper;
use App\Http\Controllers\Traits\CanPaginate;
use App\Models\Course;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;

class ListCourseQueryController
{
    use CanPaginate;

    /**
     * @var CourseController
     */
    protected $courseController;

    /**
     * Constructor
     * @param CourseController $courseController
     */
    public function __construct(CourseController $courseController)
    {
        $this->courseController = $courseController;
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
            $institution_code = CraydelHelperFunctions::toCleanString($request->input('institution_code'));
            $course_type = CraydelHelperFunctions::toCleanString($request->input('course_type'));
            $batch_no = CraydelHelperFunctions::toCleanString($request->input('batch_no'));
            $search_term = CraydelHelperFunctions::toCleanString($request->input('search_term'));
            $courses = Course::where('id', '>', 0)->where('is_deleted', '=', 0)->where('is_flagged_deletion', '=', 0);
            if (!empty($institution_code)) {
                $courses = $courses
                    ->where('institution_code', $institution_code);
            }
            if (!empty($course_type)) {
                $courses = $courses
                    ->where('course_type', $course_type);
            }
            if (!empty($batch_no)) {
                $courses = $courses
                    ->where('batch_no', $batch_no);
            }
            if (!empty($search_term)) {
                $courses = $courses->where('course_name', 'LIKE', '%' . $search_term . '%');
            }
            $currentPage = !empty($currentPage) ? $currentPage : 1;
            $this->currentPaginationPage = $currentPage;
            $this->totalNumberOfEntities = $courses->count('id');
            $this->itemsPerPage = config('craydle.items_per_page', 20);
            Paginator::currentPageResolver(function () use ($currentPage) {
                return $currentPage;
            });
            $courses = $courses
                ->orderBy('id', 'DESC')
                ->simplePaginate($this->itemsPerPage);
            return $this->courseController->respondInJSON((new CraydelJSONResponseType(
                true,
                LanguageTranslationHelper::translate('courses.success.listed'),
                call_user_func(function () use ($courses) {
                    return [
                        'items' => call_user_func(function () use ($courses) {
                            $items = is_callable(array($courses, 'items')) ? $courses->items() : array();
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
            $this->courseController->logException($exception);
            return $this->courseController->respondInJSON((new CraydelJSONResponseType(
                false,
                LanguageTranslationHelper::translate('courses.errors.list_general_errors')
            )));
        }
    }
}
