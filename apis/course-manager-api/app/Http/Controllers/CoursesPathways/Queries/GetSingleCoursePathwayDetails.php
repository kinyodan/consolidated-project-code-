<?php

namespace App\Http\Controllers\CoursesPathways\Queries;

use App\Http\Controllers\CoursesPathways\CoursesPathwaysController;
use App\Http\Controllers\CraydelTypes\CraydelJSONResponseType;
use App\Http\Controllers\Helpers\CraydelHelperFunctions;
use App\Http\Controllers\Helpers\LanguageTranslationHelper;
use App\Http\Controllers\Traits\CanPaginate;
use App\Models\CareerPathway;
use App\Models\CoursesPathway;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Log;

class GetSingleCoursePathwayDetails
{
    use CanPaginate;

    protected CoursesPathwaysController $coursesPathwaysController;


    public function __construct(CoursesPathwaysController $coursesPathwaysController)
    {
        $this->coursesPathwaysController = $coursesPathwaysController;
    }

    public function get(Request $request): JsonResponse
    {
        try {
            $currentPage = $request->input('page');
            $name=CraydelHelperFunctions::toCleanString($request->input('name'));
            if (empty($name)) {
                return $this->coursesPathwaysController->respondInJSON(new CraydelJSONResponseType(
                    false,
                    LanguageTranslationHelper::translate('pathways.errors.missing_career_pathways_name')
                ));
            }
            $details = CareerPathway::where('career_pathway_name', '=', $name);
            $currentPage = !empty($currentPage) ? $currentPage : 1;
            $this->currentPaginationPage = $currentPage;
            $this->totalNumberOfEntities = $details->count('id');
            $this->itemsPerPage = config('craydle.items_per_page', 20);
            Paginator::currentPageResolver(function () use ($currentPage) {
                return $currentPage;
            });

            $details = $details
                ->orderBy('id', 'DESC')
                ->simplePaginate($this->itemsPerPage);

            return $this->coursesPathwaysController->respondInJSON((new CraydelJSONResponseType(
                true,
                LanguageTranslationHelper::translate('pathways.success.select'),
                call_user_func(function () use ($details) {
                    return [
                        'items' => call_user_func(function () use ($details) {
                            $items = is_callable(array($details, 'items')) ? $details->items() : array();

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
