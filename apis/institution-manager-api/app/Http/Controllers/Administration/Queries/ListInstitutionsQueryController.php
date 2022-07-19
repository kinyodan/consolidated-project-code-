<?php

namespace App\Http\Controllers\Administration\Queries;

use App\Http\Controllers\CraydelTypes\CraydelJSONResponseType;
use App\Http\Controllers\Helpers\CraydelHelperFunctions;
use App\Http\Controllers\Helpers\LanguageTranslationHelper;
use App\Http\Controllers\Administration\InstitutionController;
use App\Http\Controllers\Traits\CanCache;
use App\Http\Controllers\Traits\CanPaginate;
use App\Models\Country;
use App\Models\Questions;
use App\Models\Institution;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;

class ListInstitutionsQueryController
{
    use CanPaginate, CanCache;

    /**
     * @var InstitutionController
     */
    protected $institutionController;

    /**
     * Constructor
     * @param InstitutionController $institutionController
     */
    public function __construct(InstitutionController $institutionController)
    {
        $this->institutionController = $institutionController;
    }

    /**
     * List institutions
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function getInstitutions(Request $request): JsonResponse
    {
        try {
            $currentPage = $request->input('page');
            $noPaging = $request->input('no_paging');
            $country_code = CraydelHelperFunctions::toCleanString($request->input('country_code'));

            $institutions = Institution::where('id', '>', 0);

            if (!empty($country_code)) {
                $institutions = $institutions->where('country_code', $country_code);
            }

            $currentPage = !empty($currentPage) ? $currentPage : 1;
            $this->currentPaginationPage = $currentPage;
            $this->totalNumberOfEntities = $institutions->count('id');
            $this->itemsPerPage = config('craydle.items_per_page', 20);

            if ($noPaging) {
                $institutions = $institutions
                    ->orderBy('id', 'desc')
                    ->simplePaginate($this->totalNumberOfEntities);
            } else {
                Paginator::currentPageResolver(function () use ($currentPage) {
                    return $currentPage;
                });

                $institutions = $institutions
                    ->orderBy('id', 'desc')
                    ->simplePaginate($this->itemsPerPage);
            }

            return $this->institutionController->respondInJSON((new CraydelJSONResponseType(
                true,
                LanguageTranslationHelper::translate('institutions.success.listed'),
                call_user_func(function () use ($institutions) {
                    return [
                        'items' => call_user_func(function () use ($institutions) {
                            $items = is_callable(array($institutions, 'items')) ? $institutions->items() : array();
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
                        'number_of_pages' => $this->totalNumberOfPages,
                        'items_count' => $this->totalNumberOfEntities
                    ];
                })
            )));
        } catch (Exception $exception) {
            $this->institutionController->logException($exception);

            return $this->institutionController->respondInJSON((new CraydelJSONResponseType(
                false,
                LanguageTranslationHelper::translate('institutions.errors.list_general_errors')
            )));
        }
    }

    /**
     * Get institutions list for RPC
     */
    public function institutions(): JsonResponse
    {
        try {
            return $this->institutionController->respondInJSON(new CraydelJSONResponseType(
                true,
                'List',
                Institution::active()
                    ->orderBy('institution_name')
                    ->get()
                    ->toArray()
            ));
        } catch (Exception $exception) {
            $this->institutionController->logException($exception);

            return $this->institutionController->respondInJSON((new CraydelJSONResponseType(
                false,
                LanguageTranslationHelper::translate('institutions.errors.list_general_errors')
            )));
        }
    }

    /**
     * Get the institution names and institution code only
    */
    public function activeInstitutionNames(): JsonResponse
    {
        try {
            $institution_names = self::cache("ACTIVE_INSTITUTION_NAMES");

            if(empty($institution_names)){
                $institution_names = Institution::active()
                    ->orderBy('institution_name')
                    ->get()
                    ->map(function ($institution){
                        return [
                            'institution_name' => $institution->institution_name ?? null,
                            'institution_code' => $institution->institution_code ?? null
                        ];
                    })->reject(function ($institution){
                        return !isset($institution['institution_name']);
                    })->toArray();

                self::cache("ACTIVE_INSTITUTION_NAMES", $institution_names);
            }

            return $this->institutionController->respondInJSON(new CraydelJSONResponseType(
                true,
                'List',
                $institution_names
            ));
        } catch (Exception $exception) {
            $this->institutionController->logException($exception);

            return $this->institutionController->respondInJSON((new CraydelJSONResponseType(
                false,
                LanguageTranslationHelper::translate('institutions.errors.list_general_errors')
            )));
        }
    }

    /**
     * Get supported currencies
     */
    public function currencies(): JsonResponse
    {
        try {
            return $this->institutionController->respondInJSON(new CraydelJSONResponseType(
                true,
                'List',
                DB::table((new Country())->getTable())
                    ->orderBy('currency_name')
                    ->get([
                        'currency_code', 'currency_name'
                    ])
                    ->toArray()
            ));

        } catch (Exception $exception) {
            $this->institutionController->logException($exception);

            return $this->institutionController->respondInJSON((new CraydelJSONResponseType(
                false,
                $exception->getMessage()
            )));
        }
    }

    /**
     * Get supported countries
     */
    public function countries(): JsonResponse
    {
        try {
            return $this->institutionController->respondInJSON(new CraydelJSONResponseType(
                true,
                'List',
                DB::table((new Country())->getTable())
                    ->orderBy('name')
                    ->get([
                        'iso_code', 'name',
                        'continent', 'geographical_region'
                    ])
                    ->toArray()
            ));

        } catch (Exception $exception) {
            $this->institutionController->logException($exception);

            return $this->institutionController->respondInJSON((new CraydelJSONResponseType(
                false,
                $exception->getMessage()
            )));
        }
    }

    /**
     * Get institution details by name
     * @param string $institution_name
     * @return JsonResponse
     */
    public function getInstitutionDetailsByName(string $institution_name): JsonResponse
    {
        try {
            return $this->institutionController->respondInJSON(new CraydelJSONResponseType(
                true,
                'Details',
                Institution::with(['country'])
                    ->where('institution_name_slug', CraydelHelperFunctions::slugifyString($institution_name))
                    ->first()
                    ->toArray()
            ));
        } catch (Exception $exception) {
            $this->institutionController->logException($exception);

            return $this->institutionController->respondInJSON((new CraydelJSONResponseType(
                false,
                $exception->getMessage()
            )));
        }
    }

    public function questions(): JsonResponse
    {
        $questions = Questions::join('question_categories', 'question_categories.id', '=', 'questions.question_category_id')
            ->select('question_categories.id as category_id','question_categories.title as category','questions.id as question_id','questions.title as title', 'questions.description as description');
        $currentPage = !empty($currentPage) ? $currentPage : 1;
        $this->currentPaginationPage = $currentPage;
        $this->totalNumberOfEntities = $questions->count('questions.id');
        $this->itemsPerPage = 20;
        Paginator::currentPageResolver(function () use ($currentPage) {
            return $currentPage;
        });
        $questions = $questions
            ->orderBy('questions.id', 'ASC')
            ->simplePaginate($this->itemsPerPage);
        return $this->institutionController->respondInJSON((new CraydelJSONResponseType(
            true,
            LanguageTranslationHelper::translate('alumni.success.select'),
            call_user_func(function () use ($questions) {
                return [
                    'items' => call_user_func(function () use ($questions) {
                        $items = is_callable(array($questions, 'items')) ? $questions->items() : array();

                        $_list = [];

                        foreach ($items as $item) {

                            $_list[] = [
                                'category' => $item->category,
                                'questions' => $item
                            ];
                        }

                        $groups = collect($_list)->groupBy('category')->toArray();
                        $subs = [];

                        foreach ($groups as $group) {
                            $subs [] = [
                                'category' => $group[0]['category'],
                                'questions' => call_user_func(function () use ($group) {
                                    $clusters = [];

                                    foreach ($group as $item) {
                                        $clusters [] = $item['questions'];
                                    }

                                    return $clusters;
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
    }

}
