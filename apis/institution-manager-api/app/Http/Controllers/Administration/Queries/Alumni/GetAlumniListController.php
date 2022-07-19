<?php

namespace App\Http\Controllers\Administration\Queries\Alumni;

use App\Http\Controllers\Administration\InstitutionController;
use App\Http\Controllers\CraydelTypes\CraydelJSONResponseType;
use App\Http\Controllers\Helpers\LanguageTranslationHelper;
use App\Models\InstitutionAlumnus;
use App\Http\Controllers\Traits\CanCache;
use App\Http\Controllers\Traits\CanPaginate;
use Exception;
use Illuminate\Pagination\Paginator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GetAlumniListController
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

    public function alumniList(Request $request): JsonResponse
    {
        try {

            $alumnus = InstitutionAlumnus::where('id', '>', 0);
            $currentPage = $request->input('page');
            $noPaging = $request->input('no_paging');

            $currentPage = !empty($currentPage) ? $currentPage : 1;
            $this->currentPaginationPage = $currentPage;
            $this->totalNumberOfEntities = $alumnus->count('id');
            $this->itemsPerPage = config('craydle.items_per_page', 10);

            if ($noPaging) {
                $alumnus = $alumnus
                    ->orderBy('id', 'desc')
                    ->simplePaginate($this->totalNumberOfEntities);
            } else {
                Paginator::currentPageResolver(function () use ($currentPage) {
                    return $currentPage;
                });

                $alumnus = $alumnus
                    ->orderBy('id', 'desc')
                    ->simplePaginate($this->itemsPerPage);
            }
            return $this->institutionController->respondInJSON(new CraydelJSONResponseType(
                true,
                LanguageTranslationHelper::translate('institutions.success.alumni_shown'),
                (object)[
                    'items' => $alumnus,
                    'items_per_page' => $this->itemsPerPage,
                    'current_page' => $this->currentPaginationPage,
                    'previous_page' => $this->previousPage(),
                    'next_page' => $this->nextPage(),
                    'number_of_pages' => $this->totalNumberOfPages,
                    'items_count' => $this->totalNumberOfEntities
                ]
            ));
        } catch (Exception $exception) {
            $this->institutionController->logException($exception);

            return $this->institutionController->respondInJSON(new CraydelJSONResponseType(
                false,
                $exception->getMessage()
            ));
        }
    }
}
