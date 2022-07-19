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

class ListSubjectQueryController
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
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function get(Request $request): JsonResponse
    {
        try {
            $currentPage = $request->input('page');
            $country_id = CraydelHelperFunctions::toCleanString($request->input('country_id'));
            $search_term = CraydelHelperFunctions::toCleanString($request->input('search_term'));
            $subjects = Subject::where('is_published', '=', 1)
                ->join('countries', 'countries.id', '=', 'subjects.country_id')
                ->select('subjects.subject_name','subjects.id','countries.name as country_name');
            if (!empty($country_id)) {
                $subjects = $subjects->where('country_id', $country_id);
            }
            if (!empty($search_term)) {
                $subjects = $subjects->where('subject_name', 'LIKE', '%' . $search_term . '%');
            }
            $currentPage = !empty($currentPage) ? $currentPage : 1;
            $this->currentPaginationPage = $currentPage;
            $this->totalNumberOfEntities = $subjects->count('subjects.id');
            $this->itemsPerPage = config('craydle.items_per_page', 20);
            Paginator::currentPageResolver(function () use ($currentPage) {
                return $currentPage;
            });
            $subjects = $subjects
                ->orderBy('subjects.id', 'DESC')
                ->simplePaginate($this->itemsPerPage);
            return $this->subjectController->respondInJSON((new CraydelJSONResponseType(
                true,
                LanguageTranslationHelper::translate('subjects.success.listed'),
                call_user_func(function () use ($subjects) {
                    return [
                        'items' => call_user_func(function () use ($subjects) {
                            $items = is_callable(array($subjects, 'items')) ? $subjects->items() : array();
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
            $this->subjectController->logException($exception);
            return $this->subjectController->respondInJSON((new CraydelJSONResponseType(
                false,
                LanguageTranslationHelper::translate('subjects.errors.list_general_errors')
            )));
        }
    }
}
