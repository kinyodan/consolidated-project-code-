<?php

namespace App\Http\Controllers\CoursesPathways\Queries;

use App\Http\Controllers\CoursesPathways\CoursesPathwaysController;
use App\Http\Controllers\CraydelTypes\CraydelJSONResponseType;
use App\Http\Controllers\Helpers\CraydelHelperFunctions;
use App\Http\Controllers\Helpers\LanguageTranslationHelper;
use App\Http\Controllers\Traits\CanPaginate;
use App\Models\CareerPathway;
use App\Models\Cluster;
use App\Models\CoursesPathway;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Log;

class ListCoursesPathwaysQueryController
{
    use CanPaginate;

    protected CoursesPathwaysController $coursesPathwaysController;


    public function __construct(CoursesPathwaysController $coursesPathwaysController)
    {
        $this->coursesPathwaysController = $coursesPathwaysController;
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
            $pathways = CoursesPathway::where('courses_careers_pathways.is_published', '=', 1)
                ->join('academic_disciplines', 'academic_disciplines.id', '=', 'courses_careers_pathways.academic_disciplines_id')
                ->join('career_pathways', 'career_pathways.id', '=', 'courses_careers_pathways.career_pathways_id')
                ->select('academic_disciplines.id as academic_disciplines', 'academic_disciplines.discipline_name as discipline_name', 'career_pathways.career_pathway_name as career_pathway_name', 'career_pathways.id as career_pathway_id', 'courses_careers_pathways.id as courses_careers_pathways_id');
            $currentPage = !empty($currentPage) ? $currentPage : 1;
            $this->currentPaginationPage = $currentPage;
            $this->totalNumberOfEntities = $pathways->count('courses_careers_pathways.id');
            $this->itemsPerPage = config('craydle.items_per_page', 20);
            Paginator::currentPageResolver(function () use ($currentPage) {
                return $currentPage;
            });

            $pathways = $pathways
                ->orderBy('courses_careers_pathways.id', 'DESC')
                ->simplePaginate($this->itemsPerPage);

            return $this->coursesPathwaysController->respondInJSON((new CraydelJSONResponseType(
                true,
                LanguageTranslationHelper::translate('pathways.success.listed'),
                call_user_func(function () use ($pathways) {
                    return [
                        'items' => call_user_func(function () use ($pathways) {
                            $items = is_callable(array($pathways, 'items')) ? $pathways->items() : array();

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
            $this->coursesPathwaysController->logException($exception);
            return $this->coursesPathwaysController->respondInJSON((new CraydelJSONResponseType(
                false,
                LanguageTranslationHelper::translate('pathways.errors.list_general_errors')
            )));
        }
    }

    public function getCareerPathways(Request $request): JsonResponse
    {
        try {
            $currentPage = $request->input('page');
            $pathways = CareerPathway::select('id','career_pathway_name');
            $currentPage = !empty($currentPage) ? $currentPage : 1;
            $this->currentPaginationPage = $currentPage;
            $this->totalNumberOfEntities = $pathways->count('id');
            $this->itemsPerPage = 20;
            Paginator::currentPageResolver(function () use ($currentPage) {
                return $currentPage;
            });

            $pathways = $pathways
                ->orderBy('id', 'ASC')
                ->simplePaginate($this->itemsPerPage);

            return $this->coursesPathwaysController->respondInJSON((new CraydelJSONResponseType(
                true,
                LanguageTranslationHelper::translate('pathways.success.listed'),
                call_user_func(function () use ($pathways) {
                    return [
                        'items' => call_user_func(function () use ($pathways) {
                            $items = is_callable(array($pathways, 'items')) ? $pathways->items() : array();

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
            $this->coursesPathwaysController->logException($exception);
            return $this->coursesPathwaysController->respondInJSON((new CraydelJSONResponseType(
                false,
                LanguageTranslationHelper::translate('pathways.errors.list_general_errors')
            )));
        }

    }

}
