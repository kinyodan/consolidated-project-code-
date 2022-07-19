<?php
namespace App\Http\Controllers\Administration\Commands\Accreditation;

use App\Http\Controllers\Administration\InstitutionController;
use App\Http\Controllers\CraydelTypes\CraydelJSONResponseType;
use App\Http\Controllers\Helpers\LanguageTranslationHelper;
use App\Http\Controllers\Traits\CanLog;
use App\Http\Controllers\Traits\CanPaginate;
use App\Models\InstitutionAccreditation;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;

class GetInstitutionAccreditationsCommandController
{
    use CanLog, CanPaginate;

    /**
     * @var InstitutionController
     */
    protected $institutionController;

    /**
     * Constructor
     * @param InstitutionController $institutionController
     */
    public function __construct(InstitutionController $institutionController){
        $this->institutionController = $institutionController;
    }

    /**
     * Get accreditation to the institution
     *
     * @param Request $request
     * @param string|null $institution_code
     *
     * @return JsonResponse
     */
    public function get(Request $request, ?string $institution_code): JsonResponse
    {
        try {
            $currentPage = $request->input('page');
            $accreditations = InstitutionAccreditation::active($institution_code);

            $currentPage = !empty($currentPage) ? $currentPage : 1;
            $this->currentPaginationPage = $currentPage;
            $this->totalNumberOfEntities = $accreditations->count('id');
            $this->itemsPerPage = config('craydle.items_per_page', 20);

            Paginator::currentPageResolver(function () use ($currentPage) {
                return $currentPage;
            });

            $accreditations = $accreditations
                ->orderBy('id', 'desc')
                ->simplePaginate($this->itemsPerPage);

            return $this->institutionController->respondInJSON(new CraydelJSONResponseType(
                true,
                LanguageTranslationHelper::translate('institutions.success.accreditation_listed'),
                call_user_func(function () use($accreditations){
                    return [
                        'items' => call_user_func(function () use($accreditations) {
                            $items = is_callable( array( $accreditations, 'items')) ? $accreditations->items() : array();
                            $_list = [];

                            foreach ($items as $item){
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
        }catch (Exception $exception){
            $this->institutionController->logException($exception);
            return $this->institutionController->respondInJSON(new CraydelJSONResponseType(false, $exception->getMessage()));
        }
    }
}
