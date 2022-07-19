<?php
namespace App\Http\Controllers\Helpers;

use Carbon\Carbon;

class DateHelper
{
    /**
     * @var string $timezone
    */
    private static string $timezone = 'Africa/Nairobi';

    /**
     * Current date
     *
     * @returns Carbon
    */
    public static function now(): Carbon
    {
        return Carbon::now(self::$timezone);
    }

    /**
     * Compare dates
    */
    public static function diffInMinutes(string $date_one, string $date_two): int
    {
        return Carbon::parse($date_one, self::$timezone)->diffInMinutes(
          Carbon::parse($date_two, self::$timezone)
        );
    }

    /**
     * Convert to format datetime
     * @param string|null $date
     * @param string|null $format
     * @return string|null
     */
    public static function makeDisplayDateTime(?string $date, ?string $format = 'd-m-Y H:i:s'): ?string
    {
        return Carbon::parse($date, self::$timezone)->format($format);
    }
}
