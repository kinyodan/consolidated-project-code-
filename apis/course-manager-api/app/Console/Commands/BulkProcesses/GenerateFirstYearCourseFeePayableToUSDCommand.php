<?php
namespace App\Console\Commands\BulkProcesses;

use App\Http\Controllers\Courses\Commands\CalculateFirstYearFeeCommandController;
use App\Http\Controllers\Traits\CanLog;
use App\Models\Course;
use Illuminate\Support\Facades\DB;
use Illuminate\Console\Command;

class GenerateFirstYearCourseFeePayableToUSDCommand extends Command
{
    use CanLog;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bulk:courses:generate-first-year-fees';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate the first year course fees';

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
                ->where('standard_first_year_fee_payable_usd_is_manual', 0)
                ->where('foreign_student_first_year_fee_payable_usd_is_manual', 0)
                ->get([
                    'course_code',
                    'standard_fee_payable',
                    'foreign_student_fee_payable',
                    'standard_fee_payable_usd',
                    'foreign_student_fee_payable_usd',
                    'course_duration_category',
                    'course_duration'
                ]);

            if(count($courses) > 0){
                $bar = $this->output->createProgressBar(count($courses));
                $bar->start();

                foreach ($courses as $course){
                    if(isset($course->course_code) && !empty($course->course_code)){
                        DB::transaction(function () use($course, $bar){
                            $this->info("Processing Course: {$course->course_code}");

                            $update = DB::table((new Course())->getTable())
                                ->where('course_code', $course->course_code)
                                ->update([
                                    'standard_first_year_fee_payable_usd' => CalculateFirstYearFeeCommandController::calculate(
                                        $course, $course->standard_fee_payable_usd
                                    ),
                                    'foreign_student_first_year_fee_payable_usd' => CalculateFirstYearFeeCommandController::calculate(
                                        $course, $course->foreign_student_fee_payable_usd
                                    ),
                                    'has_updates' => 1,
                                    'is_picked_for_indexing' => 0,
                                    'time_picked_for_indexing' => null,
                                    'time_taken_to_index' => null,
                                    'indexing_error' => null
                                ]);

                            if($update){
                                $this->info("Updated Course: {$course->course_code}");
                            }else{
                                $this->info("Failed to update Course: {$course->course_code}");
                            }

                            $bar->advance();
                        });
                    }
                }
            }else{
                $this->info('No courses to generate first year course fee for.');
            }
        }catch (\Exception $exception){
            $this->logException($exception);
        }
    }
}
