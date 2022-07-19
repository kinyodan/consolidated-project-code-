<?php
namespace App\Http\Controllers\Administration\Queries\Highlights;

use App\Http\Controllers\Administration\InstitutionController;
use App\Http\Controllers\CraydelTypes\CraydelJSONResponseType;
use App\Http\Controllers\Helpers\CraydelHelperFunctions;
use App\Http\Controllers\Helpers\LanguageTranslationHelper;
use App\Http\Controllers\Traits\CanPaginate;
use App\Models\Institution;
use App\Models\InstitutionHighlight;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;

class GetInstitutionHighlightsQueryController
{
    use CanPaginate;

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
     * Get the institution highlights
     */
    public function get(Request $request, ?string $institution_code): JsonResponse
    {
        try {
            $currentPage = $request->input('page');
            $institution_code = CraydelHelperFunctions::toCleanString($institution_code);
            if (empty($institution_code)) {
                throw new Exception(
                    LanguageTranslationHelper::translate('institutions.errors.missing_institution_code')
                );
            }
            if (!DB::table((new Institution())->getTable())->where('institution_code', $institution_code)->exists()) {
                throw new Exception(
                    LanguageTranslationHelper::translate('institutions.errors.missing_institution_code')
                );
            }
            $institution_highlight = InstitutionHighlight::active()->where('institution_code', $institution_code);
            $currentPage = !empty($currentPage) ? $currentPage : 1;
            $this->currentPaginationPage = $currentPage;
            $this->totalNumberOfEntities = $institution_highlight->count('id');
            $this->itemsPerPage = config('craydle.items_per_page', 20);
            Paginator::currentPageResolver(function () use ($currentPage) {
                return $currentPage;
            });
            $institution_highlight = $institution_highlight
                ->orderBy('id', 'desc')
                ->simplePaginate($this->itemsPerPage);
            return $this->institutionController->respondInJSON(new CraydelJSONResponseType(
                true,
                LanguageTranslationHelper::translate('institutions.success.highlights_listed'),
                call_user_func(function () use ($institution_highlight) {
                    return [
                        'items' => call_user_func(function () use ($institution_highlight) {
                            $items = is_callable(array($institution_highlight, 'items')) ? $institution_highlight->items() : array();
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
                        'number_of_pages' => !is_null($this->totalNumberOfPages) ? $this->totalNumberOfPages : 1,
                        'items_count' => $this->totalNumberOfEntities
                    ];
                })
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
