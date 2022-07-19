<?php
namespace App\Http\Controllers\Providers\Forex;

use App\Http\Controllers\Helpers\CraydelInternalResponseHelper;

interface IForex
{
    /**
     * Constructor
     *
     * @param string|null $from_currency
     * @param string|null $to_currency
     * @param float|null $amount
    */
    public function __construct(?string $from_currency, ?string $to_currency, ?float $amount);

    /**
     * Convert currency
     * @return CraydelInternalResponseHelper
     */
    public function convert(): CraydelInternalResponseHelper;

    /**
     * Latest rates
    */
    public function latest(): CraydelInternalResponseHelper;
}
