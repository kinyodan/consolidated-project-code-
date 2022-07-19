<?php
namespace App\Http\Controllers\Providers\Forex\Fixer;

use App\Http\Controllers\Helpers\CraydelHelperFunctions;
use App\Http\Controllers\Helpers\CraydelInternalResponseHelper;
use App\Http\Controllers\Helpers\RequestHelper;
use App\Http\Controllers\Providers\Forex\IForex;
use App\Http\Controllers\Traits\CanCache;
use App\Http\Controllers\Traits\CanLog;
use App\Http\Controllers\Traits\CanRespond;
use Exception;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class FixerForexCommandController implements IForex
{
    use CanCache, CanLog, CanRespond;

    /**
     * @var string|null $from_currency
    */
    protected ?string $from_currency;

    /**
     * @var string|null $to_currency
    */
    protected ?string $to_currency;

    /**
     * @var float|null $amount
    */
    protected ?float $amount;

    /**
     * FixerForexCommandController constructor.
     *
     * @param string|null $from_currency
     * @param string|null $to_currency
     * @param float|null $amount
     */
    public function __construct(?string $from_currency = null, ?string $to_currency = null, ?float $amount = null){
        $this->from_currency = $from_currency;
        $this->to_currency = $to_currency;
        $this->amount = $amount;
    }

    /**
     * @return CraydelInternalResponseHelper
     */
    public function convert(): CraydelInternalResponseHelper
    {
        try{
            if(empty($this->from_currency)){
                throw new Exception("Missing from currency when converting amount");
            }

            if(empty($this->to_currency)){
                throw new Exception("Missing to currency when converting amount");
            }

            if(empty($this->amount)){
                throw new Exception("Missing amount convert.");
            }

            $conversion = self::cache(
                CraydelHelperFunctions::slugifyString($this->from_currency.'_'.$this->to_currency.'_'.$this->amount)
            );

            if(!empty($conversion)){
                return $this->internalResponse(new CraydelInternalResponseHelper(
                    true,
                    "Converted",
                    (object)[
                        'converted_value' => floatval($conversion),
                        'from' => $this->from_currency,
                        'to' => $this->to_currency,
                        'amount' => $this->amount
                    ]
                ));
            }

            $api_key = config('services.fixer_forex.api_key');

            if(empty($api_key)){
                throw new Exception("Missing or invalid Fixer forex API key.");
            }

            $api_url = sprintf(
                config('services.fixer_forex.endpoints.convert_single_value'),
                $api_key,
                $this->from_currency,
                $this->to_currency,
                intval(round($this->amount))
            );

            $request = RequestHelper::get($api_url,null,null, false);

            if(!$request->status){
                throw new Exception(isset($request->message) && !empty($request->message) ? $request->message : "Currency conversion error");
            }

            if(!isset($request->data) || empty($request->data)){
                throw new Exception("Currency conversion error");
            }

            $result = json_decode($request->data);

            if(!isset($result->success) || !$result->success){
                throw new Exception("Currency conversion error");
            }

            if(!isset($result->result) || empty($result->result)){
                throw new Exception("Currency conversion error");
            }

            self::cache(
                CraydelHelperFunctions::slugifyString($this->from_currency.'_'.$this->to_currency.'_'.$this->amount),
                floatval($result->result),
                config('craydle.system.cache.forex_api_cache_length')
            );

            return $this->internalResponse(new CraydelInternalResponseHelper(
                true,
                "Converted",
                (object)[
                    'converted_value' => floatval($result->result),
                    'from' => $this->from_currency,
                    'to' => $this->to_currency,
                    'amount' => floatval($result->result),
                ]
            ));
        }catch (Exception $exception){
            $this->logException($exception);

            return $this->internalResponse(new CraydelInternalResponseHelper(
                false,
                $exception->getMessage(),
                null,
                $exception
            ));
        }
    }

    /**
     * Latest exchange rate against the USD
    */
    public function latest(): CraydelInternalResponseHelper{
        try{
            $latest_usd_rates = self::cache('forex_latest_usd_rates');

            if(!is_null($latest_usd_rates)){
                return $this->internalResponse(new CraydelInternalResponseHelper(
                    true,
                    'Latest',
                    (object)[
                        'rates' => $latest_usd_rates
                    ]
                ));
            }

            $api_key = config('services.fixer_forex.api_key');

            if(empty($api_key)){
                throw new Exception("Missing or invalid Fixer forex API key.");
            }

            $api_url = sprintf(
                config('services.fixer_forex.endpoints.latest_exchange_rate_again_usd'),
                $api_key
            );

            $request = RequestHelper::get(
                $api_url,
                null,
                null,
                false
            );

            if(!$request->status){
                throw new Exception(isset($request->message) && !empty($request->message) ? $request->message : "Latest USD conversion rate error.");
            }

            if(!isset($request->data) || empty($request->data)){
                throw new Exception("Latest USD conversion rate error.");
            }

            $result = json_decode($request->data);

            if(!isset($result->success) || !$result->success){
                throw new Exception("Latest USD conversion rate error.");
            }

            if(!isset($result->rates) || empty($result->rates)){
                throw new Exception("Latest USD conversion rate error.");
            }

            self::cache(
                'forex_latest_usd_rates',
                $result->rates,
                config('craydle.system.cache.forex_api_cache_length')
            );

            return $this->internalResponse(new CraydelInternalResponseHelper(
                true,
                "Latest Rates",
                (object)[
                    'rates' => $result->rates
                ]
            ));
        }catch (Exception $exception){
            $this->logException($exception);

            return $this->internalResponse(new CraydelInternalResponseHelper(
                false,
                $exception->getMessage(),
                null,
                $exception
            ));
        }
    }
}
