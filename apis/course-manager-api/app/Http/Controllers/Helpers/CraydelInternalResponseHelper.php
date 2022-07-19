<?php
namespace App\Http\Controllers\Helpers;

use Exception;

class CraydelInternalResponseHelper
{
    /**
     * @var bool $status
    */
    public $status;

    /**
     * @var string $message
    */
    public $message;

    /**
     * @var mixed $data
    */
    public $data;

    /**
     * @var $exception
    */
    public $exception;

    /**
     * CraydelInternalResponseHelper constructor.
     * @param bool $status
     * @param string $message
     * @param mixed $data
     * @param Exception|null $exception
     */
    public function __construct(bool $status, string $message, $data = null, $exception = null)
    {
        $this->status = $status;
        $this->message = $message;
        $this->data = $data;
        $this->exception = $exception;
    }
}
