<?php


namespace App\Http\Controllers\EducationTypes\Queries;

use App\Http\Controllers\EducationTypes\EducationTypesController;
use App\Http\Controllers\CraydelTypes\CraydelJSONResponseType;
use App\Http\Controllers\Helpers\CraydelHelperFunctions;
use App\Http\Controllers\Helpers\LanguageTranslationHelper;
use App\Http\Controllers\Traits\CanPaginate;
use App\Models\EducationType;
use App\Models\Subject;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;

class ListEducationTypeQueryController
{
    use CanPaginate;

    /**
     * @var EducationTypesController
     */
    protected EducationTypesController $educationTypesController;

    /**
     * Constructor
     * @param EducationTypesController $educationTypesController
     */
    public function __construct(EducationTypesController $educationTypesController)
    {
        $this->educationTypesController = $educationTypesController;
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
            $educationtypes= EducationType::where('is_published', '=', 1)
                ->join('countries','countries.id','=','education_types.country_id')
                ->select('education_types.education_type_name','education_types.id','countries.name as country_name');
            if (!empty($country_id)) {
                $educationtypes = $educationtypes->where('country_id', $country_id);
            }
            if (!empty($search_term)) {
                $educationtypes = $educationtypes->where('education_type_name', 'LIKE', '%' . $search_term . '%');
            }
            $currentPage = !empty($currentPage) ? $currentPage : 1;
            $this->currentPaginationPage = $currentPage;
            $this->totalNumberOfEntities = $educationtypes->count('education_types.id');
            $this->itemsPerPage = config('craydle.items_per_page', 20);
            Paginator::currentPageResolver(function () use ($currentPage) {
                return $currentPage;
            });
            $educationtypes = $educationtypes
                ->orderBy('education_types.id', 'DESC')
                ->simplePaginate($this->itemsPerPage);
            return $this->educationTypesController->respondInJSON((new CraydelJSONResponseType(
                true,
                LanguageTranslationHelper::translate('educations.success.listed'),
                call_user_func(function () use ($educationtypes) {
                    return [
                        'items' => call_user_func(function () use ($educationtypes) {
                            $items = is_callable(array($educationtypes, 'items')) ? $educationtypes->items() : array();
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
            $this->educationTypesController->logException($exception);
            return $this->educationTypesController->respondInJSON((new CraydelJSONResponseType(
                false,
                LanguageTranslationHelper::translate('educations.errors.list_general_errors')
            )));
        }
    }
}
