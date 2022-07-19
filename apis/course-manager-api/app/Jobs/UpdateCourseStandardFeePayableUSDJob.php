<?php
namespace App\Jobs;

use App\Http\Controllers\Courses\CourseController;
use App\Http\Controllers\Helpers\CraydelHelperFunctions;
use App\Models\Course;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\DB;

class UpdateCourseStandardFeePayableUSDJob extends Job
{
    use Queueable;

    /**
     * @var string|null $course_code
    */
    protected ?string $course_code;

    /**
     * @var string|null $local_currency_code
    */
    protected ?string $local_currency_code;

    /**
     * @var float|null $standard_fee_payable
    */
    protected ?float $standard_fee_payable;

    /**
     * Create a new job instance.
     *
     * @param string|null $course_code
     * @param string|null $local_currency_code
     * @param float|null $standard_fee_payable
     */
    public function __construct(?string $course_code, ?string $local_currency_code, ?float $standard_fee_payable)
    {
        $this->course_code = $course_code;
        $this->local_currency_code = $local_currency_code;
        $this->standard_fee_payable = $standard_fee_payable;
    }

    /**
     * Execute the job.
     *
     * @return void
     * @throws Exception
     */
    public function handle()
    {
        CourseController::updateCourseStandardFeePayableUSD(
            $this->course_code,
            $this->local_currency_code,
            $this->standard_fee_payable
        );
    }
}
