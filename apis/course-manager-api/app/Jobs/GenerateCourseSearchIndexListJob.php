<?php
namespace App\Jobs;

use App\Http\Controllers\Courses\Commands\GenerateCourseSearchIndexListCommandController;
use App\Http\Controllers\Courses\CourseController;
use App\Http\Controllers\Traits\CanLog;
use App\Models\Course;
use Exception;
use Illuminate\Support\Facades\DB;

class GenerateCourseSearchIndexListJob extends Job
{
    use CanLog;

    /**
     * @var string|null $course_code
    */
    protected ?string $course_code;

    /**
     * Create a new job instance.
     *
     * @param ?string $course_code
     *
     */
    public function __construct(string $course_code)
    {
        $this->course_code = $course_code;
    }

    /**
     * Execute the job.
     *
     * @return void
     * @throws Exception
     */
    public function handle()
    {
        if(!empty($this->course_code)){
            $course_details = DB::table((new Course())->getTable())
                ->where('course_code', $this->course_code)
                ->first();

            CourseController::updateCourseStandardFeePayableUSD(
                $this->course_code,
                $course_details->currency,
                $course_details->standard_fee_payable
            );

            CourseController::updateCourseForeignStudentFeePayableUSD(
                $this->course_code,
                $course_details->currency,
                $course_details->foreign_student_fee_payable
            );

            GenerateCourseSearchIndexListCommandController::generate($this->course_code);
        }
    }
}
