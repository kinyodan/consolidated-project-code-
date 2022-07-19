<?php
namespace App\Console\Commands\BulkProcesses;

use App\Http\Controllers\Traits\CanLog;
use App\Jobs\UpdateCourseForeignStudentFeePayableUSDJob;
use App\Jobs\UpdateCourseStandardFeePayableUSDJob;
use App\Models\Course;
use Illuminate\Support\Facades\DB;
use Illuminate\Console\Command;

class UpdateCourseStandardFeePayableToUSDCommand extends Command
{
    use CanLog;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bulk:courses:update-standard-fee-payable-to-usd';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update the course standard fee payable to USD.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle() {
        try{
            $courses = DB::table((new Course())->getTable())
                ->where('is_deleted', 0)
                ->where('is_active', 1)
                ->get([
                    'country_code',
                    'course_code',
                    'standard_fee_payable',
                    'foreign_student_fee_payable',
                    'currency'
                ]);

            if(count($courses) > 0){
                foreach ($courses as $course){
                    if(isset($course->course_code) && !empty($course->course_code)){
                        dispatch((new UpdateCourseStandardFeePayableUSDJob(
                            $course->course_code,
                            $course->currency ?? null,
                            $course->standard_fee_payable ?? null
                        )))->onQueue('update_standard_fee_payable_usd');

                        dispatch((new UpdateCourseForeignStudentFeePayableUSDJob(
                            $course->course_code,
                            $course->currency ?? null,
                            $course->foreign_student_fee_payable ?? null
                        )))->onQueue('update_standard_fee_payable_usd');
                    }
                }
            }else{
                $this->info('No courses to update the USD fees.');
            }
        }catch (\Exception $exception){
            $this->logException($exception);
        }
    }
}
