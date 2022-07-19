<?php
namespace App\Http\Controllers\Traits;

trait CanPaginate
{
    /**
     * @var $totalNumberOfEntities
     */
    protected $totalNumberOfEntities;

    /**
     * @var $currentPaginationPage
     */
    protected $currentPaginationPage;

    /**
     * @var $itemsPerPage
     */
    protected $itemsPerPage;

    /**
     * @var $totalNumberOfPages
    */
    protected $totalNumberOfPages;

    /**
     * @return mixed
     */
    public function getTotalNumberOfPages()
    {
        $itemsPerPage = $this->itemsPerPage;

        if(empty($itemsPerPage)){
            $this->itemsPerPage = config('craydle.items_per_page');
        }

        $this->totalNumberOfPages = intval(ceil(intval($this->totalNumberOfEntities) / intval($itemsPerPage)));
        return $this->totalNumberOfPages;
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

        if(intval($pages) == intval($this->currentPaginationPage)){
            return $this->currentPaginationPage;
        }elseif (intval($pages) > intval($this->currentPaginationPage)){
            $nextPage = intval($this->currentPaginationPage) + 1;
            return intval($nextPage) < intval($pages) ? $nextPage : $pages;
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

        $pages = intval(ceil(intval($this->totalNumberOfEntities) / intval($itemsPerPage)));

        if($this->currentPaginationPage == 1){
            return $this->currentPaginationPage;
        }

        if(intval($pages) === intval($this->currentPaginationPage)){
            return intval($this->currentPaginationPage - 1);
        }elseif (intval($pages) > intval($this->currentPaginationPage)){
            return intval($this->currentPaginationPage - 1);
        }else{
            return intval(1);
        }
    }
}
