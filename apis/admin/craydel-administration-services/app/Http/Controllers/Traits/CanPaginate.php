<?php
namespace App\Http\Controllers\Traits;

use App\Http\Controllers\Helpers\CraydelHelperFunctions;
use Illuminate\Http\Request;

trait CanPaginate
{
    /**
     * @var int|null $totalNumberOfEntities
     */
    protected ?int $totalNumberOfEntities;

    /**
     * @var int $currentPaginationPage
     */
    protected int $currentPaginationPage;

    /**
     * @var int $itemsPerPage
     */
    protected int $itemsPerPage;

    /**
     * @var int $totalNumberOfPages
    */
    protected int $totalNumberOfPages;

    /**
     * @return int
     */
    public function getTotalNumberOfPages(): int
    {
        $itemsPerPage = $this->itemsPerPage;

        if(empty($itemsPerPage)){
            $this->itemsPerPage = config('craydle.items_per_page');
        }

        $this->totalNumberOfPages = intval(ceil(intval($this->totalNumberOfEntities) / $itemsPerPage));

        return $this->totalNumberOfPages ?? 0;
    }

    /**
     * Next page
     */
    public function nextPage(): int
    {
        $itemsPerPage = $this->itemsPerPage;

        if(empty($itemsPerPage)){
            $this->itemsPerPage = config('craydle.items_per_page');
        }

        if(empty($this->currentPaginationPage)){
            $this->currentPaginationPage = 1;
        }

        $pages = intval(ceil(intval($this->totalNumberOfEntities) / intval($itemsPerPage)));

        if($pages == $this->currentPaginationPage){
            return $this->currentPaginationPage;
        }elseif ($pages > $this->currentPaginationPage){
            $nextPage = $this->currentPaginationPage + 1;
            return min($nextPage, $pages);
        }

        return 1;
    }

    /**
     * Previous page
     */
    public function previousPage(): int
    {
        $itemsPerPage = $this->itemsPerPage;

        if(empty($itemsPerPage)){
            $this->itemsPerPage = config('craydle.items_per_page');
        }

        if(empty($this->currentPaginationPage)){
            $this->currentPaginationPage = 1;
        }

        $pages = intval(ceil(intval($this->totalNumberOfEntities) / $itemsPerPage));

        if($this->currentPaginationPage == 1){
            return $this->currentPaginationPage;
        }

        if($pages === $this->currentPaginationPage){
            return $this->currentPaginationPage - 1;
        }elseif ($pages > $this->currentPaginationPage){
            return $this->currentPaginationPage - 1;
        }else{
            return 1;
        }
    }

    /**
     * Get the current page
     *
     * @param Request $request
     * @return int
     */
    public function getCurrentPage(Request $request): int
    {
        $page = $request->input('page', 1);
        return !CraydelHelperFunctions::isNull(CraydelHelperFunctions::toNumbers($page)) ? CraydelHelperFunctions::toNumbers($page) : 1;
    }
}
