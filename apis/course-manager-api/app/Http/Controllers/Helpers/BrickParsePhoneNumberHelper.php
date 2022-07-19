<?php

namespace App\Http\Controllers\Helpers;

use App\Http\Controllers\Traits\CanLog;
use App\Models\Countries;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Brick\PhoneNumber\PhoneNumber;

class BrickParsePhoneNumberHelper
{

    use CanLog;

    public static function getCountryCodeFromMobileNumber(?string $mobileNumber): ?string
    {
        try {
            if (empty($mobileNumber)) {
                return null;
            }
            $mobileNumber = '+' . $mobileNumber;
            $mobileNumber = PhoneNumber::parse($mobileNumber);
            return $mobileNumber->getRegionCode();
        } catch (\Exception $exception) {
            (new self())->logException($exception);
            return null;
        }
    }
}
