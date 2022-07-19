<?php
namespace App\Http\Controllers\Helpers;

use Illuminate\Contracts\Pagination\Paginator;

class CraydelInternalResponseWithPaginationHelper
{
    /**
     * @var bool $status
    */
    public bool $status;

    /**
     * @var string $message
    */
    public string $message;

    /**
     * @var Paginator|null $data
    */
    public ?Paginator $data;

    /**
     * @var int|null $itemsPage
    */
    public ?int $itemsPage;

    /**
     * @var int|null $currentPage
    */
    public ?int $currentPage;

    /**
     * @var int|null $nextPage
    */
    public ?int $nextPage;

    /**
     * @var int|null $previousPage
    */
    public ?int $previousPage;

    /**
     * @var int|null $numberOfPages
    */
    public ?int $numberOfPages;

    /**
     * @var int|null $total
    */
    public ?int $total;

    /**
     * CraydelInternalResponseHelper constructor.
     * @param bool $status
     * @param string $message
     * @param Paginator|null $data
     * @param int|null $itemsPage
     * @param int|null $currentPage
     * @param int|null $nextPage
     * @param int|null $previousPage
     * @param int|null $numberOfPages
     * @param int|null $total
     * @parent $itemsPage
     * @parent int|null $currentPage
     */
    public function __construct(bool $status, string $message, ?Paginator $data, ?int $itemsPage, ?int $currentPage, ?int $nextPage, ?int $previousPage, ?int $numberOfPages, ?int $total)
    {
        $this->status = $status;
        $this->message = $message;
        $this->data = $data;
        $this->itemsPage = $itemsPage;
        $this->currentPage = $currentPage;
        $this->nextPage = $nextPage;
        $this->previousPage = $previousPage;
        $this->numberOfPages = $numberOfPages;
        $this->total = $total;
    }
}
