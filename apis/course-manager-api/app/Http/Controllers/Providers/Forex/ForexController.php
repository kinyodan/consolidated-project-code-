<?php
namespace App\Http\Controllers\Providers\Forex;

use App\Http\Controllers\Helpers\CraydelHelperFunctions;
use App\Http\Controllers\Helpers\CraydelInternalResponseHelper;
use App\Http\Controllers\Traits\CanCache;
use App\Http\Controllers\Traits\CanLog;
use Illuminate\Support\Facades\App;

class ForexController
{
    use CanLog, CanCache;

    /**
     * @var string|null $from_currency
    */
    private ?string $from_currency;

    /**
     * @var string|null $to_currency
    */
    private ?string $to_currency;

    /**
     * @var float|null $amount
    */
    private ?float $amount;

    /**
     * @var IForex $conversion_provider
    */
    protected $conversion_provider;

    /**
     * ForexController constructor.
     * @param string|null $from_currency
     * @param string|null $to_currency
     * @param float|null $amount
     */
    public function __construct(?string $from_currency, ?string $to_currency, ?float $amount)
    {
        $this->from_currency = strtoupper(strtolower($from_currency));
        $this->to_currency = strtoupper(strtolower($to_currency));
        $this->amount = CraydelHelperFunctions::toNumbers($amount);

        $this->conversion_provider = App::make(IForex::class,[
            'from_currency' => $this->from_currency,
            'to_currency' => $this->to_currency,
            'amount' => $this->amount
        ]);

        $this->from_currency = $from_currency;
        $this->to_currency = $to_currency;
        $this->amount = CraydelHelperFunctions::toNumbers($amount);
    }

    /**
     * @return CraydelInternalResponseHelper
     */
    public function convert(): CraydelInternalResponseHelper
    {
        try{
            return $this->conversion_provider->convert();
        }catch (\Exception $exception){
            $this->logException($exception);

            return new CraydelInternalResponseHelper(
                false, $exception->getMessage(),
                null
            );
        }
    }
}
