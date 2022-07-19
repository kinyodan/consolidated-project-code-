<?php
namespace App\Http\Controllers\Administration\Queries\Alumni;

use App\Http\Controllers\Administration\InstitutionController;
use App\Http\Controllers\CraydelTypes\CraydelJSONResponseType;
use App\Http\Controllers\Helpers\LanguageTranslationHelper;
use App\Http\Controllers\Traits\CanPaginate;
use App\Models\InstitutionAlumnus;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;

class GetInstitutionAlumniQueryController
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
    public function __construct(InstitutionController $institutionController){
        $this->institutionController = $institutionController;
    }

    /**
     * Get institution alumni
     *
     * @param Request $request
     * @param string|null $institution_code
     * @return JsonResponse
     */
    public function get(Request $request, ?string $institution_code): JsonResponse
    {
        try{
            $currentPage = $request->input('page');
            $institution_alumnus = InstitutionAlumnus::active()->where('institution_code', $institution_code);

            $currentPage = !empty($currentPage) ? $currentPage : 1;
            $this->currentPaginationPage = $currentPage;
            $this->totalNumberOfEntities = $institution_alumnus->count('id');
            $this->itemsPerPage = config('craydle.items_per_page', 20);

            Paginator::currentPageResolver(function () use ($currentPage) {
                return $currentPage;
            });

            $institution_alumnus = $institution_alumnus
                ->orderBy('id', 'desc')
                ->simplePaginate($this->itemsPerPage);

            return $this->institutionController->respondInJSON(new CraydelJSONResponseType(
                true,
                LanguageTranslationHelper::translate('institutions.success.alumni_listed'),
                call_user_func(function () use($institution_alumnus){
                    return [
                        'items' => call_user_func(function () use($institution_alumnus) {
                            $items = is_callable( array( $institution_alumnus, 'items')) ? $institution_alumnus->items() : array();
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

            return $this->institutionController->respondInJSON(new CraydelJSONResponseType(
                false,
                $exception->getMessage()
            ));
        }
    }
}
