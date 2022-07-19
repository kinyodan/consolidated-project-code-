<?php
namespace App\Jobs;

use App\Http\Controllers\Courses\CourseController;
use Exception;

class UpdateCourseForeignStudentFeePayableUSDJob extends Job
{
    /**
     * @var $course_code
    */
    protected $course_code;

    /**
     * @var string $local_currency_code
    */
    protected $local_currency_code;

    /**
     * @var float $foreign_student_fee_payable
    */
    protected $foreign_student_fee_payable;

    /**
     * Create a new job instance.
     *
     * @param string|null $course_code
     * @param string|null $local_currency_code
     * @param float|null $foreign_student_fee_payable
     */
    public function __construct(?string $course_code, ?string $local_currency_code, ?float $foreign_student_fee_payable)
    {
        $this->course_code = $course_code;
        $this->local_currency_code = $local_currency_code;
        $this->foreign_student_fee_payable = $foreign_student_fee_payable;
    }

    /**
     * Execute the job.
     *
     * @return void
     * @throws Exception
     */
    public function handle()
    {
        CourseController::updateCourseForeignStudentFeePayableUSD(
            $this->course_code,
            $this->local_currency_code,
            $this->foreign_student_fee_payable
        );
    }
}
