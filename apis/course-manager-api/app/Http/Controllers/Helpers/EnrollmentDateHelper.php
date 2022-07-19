<?php
namespace App\Http\Controllers\Helpers;

use App\Http\Controllers\Traits\CanLog;
use App\Http\Controllers\Traits\CanRespond;
use Carbon\Carbon;
use PHPUnit\Util\Exception;

class EnrollmentDateHelper
{
    use CanLog, CanRespond;

    /**
     * Valid month names
    */
    const VALID_MONTH_NAMES = [
        'January', 'February', 'March',
        'April', 'May', 'June', 'July',
        'August', 'September', 'October',
        'November', 'December', 'Jan',
        'Feb', 'Mar', 'Apr', 'May',
        'Jun', 'Jul', 'Aug', 'Sep',
        'Oct', 'Nov', 'Dec'
    ];

    /**
     * Words to move
    */
    const TO_REMOVE = [
        'AND', 'and', '&'
    ];

    /**
     * Format months
     *
     * @param array|null $months
     *
     * @return string|null
    */
    protected static function format(?array $months): ?string
    {
        if(!is_array($months)){
            throw new Exception("Invalid months list");
        }

        $this_year = [];
        $next_year = [];
        $current_month = Carbon::now()->month;

        foreach ($months as $month){
            if(!empty($month)){
                $month_current = date_parse($month)['month'];

                if(intval($month_current) < $current_month){
                    array_push($next_year, Carbon::parse($month)->monthName. " " . Carbon::now()->addYear()->year);
                }elseif(intval($month_current) > $current_month){
                    array_push($this_year, Carbon::parse($month)->monthName. " " . Carbon::now()->year);
                }elseif(intval($month_current) == $current_month){
                    array_push($this_year, Carbon::parse($month)->monthName. " " . Carbon::now()->year);
                }
            }
        }

        $dates = json_encode(array_merge($this_year, $next_year));

        return self::_order($dates);
    }

    /**
     * Make enrollment date
     *
     * @param string|null $enrollment_date
     * @return string|null
     */
    public static function make(?string $enrollment_date): ?string
    {
        try{
            if(is_null($enrollment_date)){
                return null;
            }

            if(CraydelHelperFunctions::isJson($enrollment_date)){
                return self::format(self::_fromJson($enrollment_date));
            }

            return self::format(self::_fromString($enrollment_date));
        }catch (\Exception $exception){
            (new self())->logException($exception);
            return null;
        }
    }

    /**
     * Formulate enrollment date from json
     *
     * @param string|null $enrollment_date
     *
     * @return array|null
    */
    protected static function _fromJson(?string $enrollment_date): ?array
    {
        if(empty($enrollment_date)){
            throw new Exception("Empty enrollment date provided.");
        }

        $json = json_decode($enrollment_date);

        $months = collect($json)->map(function ($date){
            return collect(array_values((array)$date))->unique()->values()->toArray();
        })->map(function ($m){
            return collect($m)->reject(function ($m){
               return empty($m);
            })->values()->toArray();
        });

        $flat_months = [];

        foreach($months as $v) {
            if(is_array($v)){
                foreach ($v as $_v){
                    $month = ucwords(strtolower(preg_replace( '/[^\W\d]*\d\w*/', '', $_v)));

                    if(!in_array($month, self::VALID_MONTH_NAMES)){
                        $month = self::_getClosestMonth($month);
                    }

                    if(!in_array($month, $flat_months) && in_array(ucwords(strtolower($month)), self::VALID_MONTH_NAMES)){
                        array_push($flat_months, $month);
                    }
                }
            }
        }

        return $flat_months;
    }

    /**
     * Formulate enrollment date from string
     *
     * @param string|null $enrollment_date
     *
     * @return array|null
    */
    protected static function _fromString(?string $enrollment_date): ?array{
        if(empty($enrollment_date)){
            throw new Exception("Empty enrollment date provided.");
        }

        $enrollment_date = explode(' ', str_replace(self::TO_REMOVE, "", $enrollment_date));

        return collect($enrollment_date)->reject(function ($month){
            $month = ucwords(strtolower(preg_replace( '/[^\W\d]*\d\w*/', '', $month)));

            if(!in_array($month, self::VALID_MONTH_NAMES)){
                $month = self::_getClosestMonth($month);
            }

            return !in_array($month, self::VALID_MONTH_NAMES);
        })->map(function ($month){
            if(!in_array($month, self::VALID_MONTH_NAMES)){
                return self::_getClosestMonth($month);
            }else{
                return $month;
            }
        })->values()->toArray();
    }

    /**
     * Get closest month if miss spelt
     *
     * @param string|null $miss_spelt_word
     *
     * @return string|null
    */
    protected static function _getClosestMonth(?string $miss_spelt_word): ?string
    {
        if(empty($miss_spelt_word)){
            return null;
        }

        $shortest = -1;
        $closest = null;

        foreach (self::VALID_MONTH_NAMES as $month) {
            $lev = levenshtein($miss_spelt_word, $month);

            if ($lev == 0) {
                $closest = $month;
                $shortest = 0;
                break;
            }

            if ($lev <= $shortest || $shortest < 0) {
                $closest  = $month;
                $shortest = $lev;
            }
        }

        return $closest;
    }

    /**
     * Order dates
    */
    protected static function _order(?string $dates): ?string{
        if(!empty($dates)){
            $dates = json_decode($dates);
            $dates_array = [];

            for ($i = 0; $i <= (count($dates) - 1); $i++){
                if(isset($dates[$i]) && !CraydelHelperFunctions::isNull($dates[$i])){
                    $day = explode(" ", $dates[$i]);

                    array_push($dates_array, [
                        'month' => isset($day[0]) && !empty($day[0]) ? $day[0] : null,
                        'year' => isset($day[1]) && !empty($day[1]) ? $day[1] : null
                    ]);
                }
            }

            $dates_array = collect($dates_array)->sortBy('year')->groupBy('year');
            $dates_array_out = [];

            foreach ($dates_array as $item){
                $dates_array_out = array_merge($dates_array_out,
                    $item->sort(function ($a, $b) {
                        $monthA = date_parse($a['month']);
                        $monthB = date_parse($b['month']);

                        return $monthA["month"] - $monthB["month"];
                    })->toArray()
                );
            }

            $dates_array_out = collect($dates_array_out)->map(function ($item){
               return "{$item['month']} {$item['year']}";
            });

            return json_encode($dates_array_out->toArray());
        }else{
            return "";
        }
    }
}
