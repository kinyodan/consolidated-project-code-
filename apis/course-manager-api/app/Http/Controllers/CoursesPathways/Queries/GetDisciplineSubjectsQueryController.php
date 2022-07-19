<?php

namespace App\Http\Controllers\CoursesPathways\Queries;

use App\Http\Controllers\CoursesPathways\CoursesPathwaysController;
use App\Http\Controllers\CraydelTypes\CraydelJSONResponseType;
use App\Http\Controllers\Helpers\CraydelHelperFunctions;
use App\Http\Controllers\Helpers\LanguageTranslationHelper;
use App\Http\Controllers\Traits\CanPaginate;
use App\Models\CareerPathway;
use App\Models\ClusterSubject;
use App\Models\CoursesPathway;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Log;

class GetDisciplineSubjectsQueryController
{
    use CanPaginate;

    protected CoursesPathwaysController $coursesPathwaysController;


    public function __construct(CoursesPathwaysController $coursesPathwaysController)
    {
        $this->coursesPathwaysController = $coursesPathwaysController;
    }

    public function get(Request $request,$discipline_id): JsonResponse
    {
        try {
            $currentPage = $request->input('page');
            $is_primary = $request->input('is_primary');
            $subjects = ClusterSubject::where('cluster_subjects.course_code', '=', $discipline_id)
                ->join('subjects', 'subjects.id', '=', 'cluster_subjects.subject_id')
                ->select('subjects.subject_name as subject','cluster_subjects.is_primary as is_primary');

            if (!empty($is_primary)) {
                $subjects = $subjects
                    ->where('cluster_subjects.is_primary', $is_primary);
            }
            $currentPage = !empty($currentPage) ? $currentPage : 1;
            $this->currentPaginationPage = $currentPage;
            $this->totalNumberOfEntities = $subjects->count('cluster_subjects.id');
            $this->itemsPerPage = config('craydle.items_per_page', 20);
            Paginator::currentPageResolver(function () use ($currentPage) {
                return $currentPage;
            });

            $subjects = $subjects
                ->orderBy('cluster_subjects.id', 'DESC')
                ->simplePaginate($this->itemsPerPage);

            return $this->coursesPathwaysController->respondInJSON((new CraydelJSONResponseType(
                true,
                LanguageTranslationHelper::translate('subjects.success.listed'),
                call_user_func(function () use ($subjects) {
                    return [
                        'items' => call_user_func(function () use ($subjects) {
                            $items = is_callable(array($subjects, 'items')) ? $subjects->items() : array();

                            $_list = [];

                            foreach ($items as $item) {
                                $_list[] = [
                                    'is_primary' => $item->is_primary,
                                    'subjects'=> $item
                                ];
                            }

                            $groups = collect($_list)->groupBy('is_primary')->toArray();
                            $subs = [];

                            foreach ($groups as $group){
                                $subs [] = [
                                    'is_primary' => $group[0]['is_primary'],
                                    'subjects' => call_user_func(function () use($group){
                                        $subjects = [];

                                        foreach ($group as $item){
                                            $subjects [] = $item['subjects'];
                                        }

                                        return $subjects;
                                    })
                                ];
                            }

                            return $subs;
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
            $this->coursesPathwaysController->logException($exception);
            return $this->coursesPathwaysController->respondInJSON((new CraydelJSONResponseType(
                false,
                LanguageTranslationHelper::translate('subjects.errors.list_general_errors')
            )));
        }
    }

}
