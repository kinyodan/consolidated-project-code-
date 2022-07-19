<?php
namespace App\Http\Controllers\Courses\Commands;

use App\Http\Controllers\Helpers\CraydelHelperFunctions;
use Exception;

class CalculateFirstYearFeeCommandController
{
    /**
     * Calculate year fee payable
     *
     * @param object|null $course
     * @param float|null $fees_payable
     * @return float|null
     * @throws Exception
     */
    public static function calculate(?object $course, ?float $fees_payable): ?float
    {
        if(is_null($course)){
            throw new Exception("Missing or Invalid course code provided while calculating the year fee payable");
        }

        $fees_payable = CraydelHelperFunctions::toNumbers($fees_payable);

        if(empty($fees_payable) || floatval($fees_payable) <= 0){
            throw new Exception("Missing or Invalid fee payable provided while calculating the year fee payable");
        }

        if(!isset($course->course_duration_category) && empty($course->course_duration_category)){
            throw new Exception("Missing or invalid course duration category");
        }

        if(!isset($course->course_duration) && empty($course->course_duration)){
            throw new Exception("Missing or invalid course duration");
        }

        if(floatval($course->course_duration) <= 0){
            throw new Exception("Missing or invalid course duration");
        }

        if($course->course_duration_category == 'Years'){
            return ceil(floatval($fees_payable)/floatval($course->course_duration));
        }else{
            return ceil($fees_payable);
        }
    }
}
