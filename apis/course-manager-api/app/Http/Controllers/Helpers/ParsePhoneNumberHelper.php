<?php

namespace App\Http\Controllers\Helpers;

use App\Http\Controllers\Traits\CanLog;
use Propaganistas\LaravelPhone\PhoneNumber;

class ParsePhoneNumberHelper
{
    use CanLog;

    /**
     * Make country phone number
     *
     * @param string|null $countryCode
     * @param string|null $mobileNumber
     * @param bool $skipPlusSign
     *
     * @return string
     */
    public static function makeNationalizedMobileNumber(?string $countryCode, ?string $mobileNumber, bool $skipPlusSign = true): ?string
    {
        try {
            if (empty($countryCode)) {
                return null;
            }

            if (empty($mobileNumber)) {
                return null;
            }

            $countryCode = strtoupper(trim($countryCode));
            $formattedMobileNumber = PhoneNumber::make($mobileNumber, $countryCode)->formatE164();

            return $skipPlusSign == false ? $formattedMobileNumber : CraydelHelperFunctions::toNumbers($formattedMobileNumber);
        } catch (\Exception $exception) {
            (new self())->logException($exception);
            return null;
        }
    }


}
