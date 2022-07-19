<?php

namespace App\Console\Commands\Support;

use App\Http\Controllers\Courses\Commands\BulkDeleteCourseCommandController;
use App\Http\Controllers\GenerateEnrollmentDetails\GenerateEnrollmentDetailsController;
use App\Http\Controllers\Traits\CanLog;
use App\Models\Course;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class GenerateEnrollmentDetails extends Command
{
    use CanLog;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'support:generate-enrollment-details';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate Enrollment Details';

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
    public function handle()
    {
        try {
            $courses = DB::table((new Course())->getTable())
                ->where('is_deleted', 0)
                ->where('is_completed_for_enrollment_details', 0)
                ->get();

            foreach ($courses as $course) {
                GenerateEnrollmentDetailsController::selectedCourse($course);
            }
        } catch (\Exception $exception) {
            $this->error($exception->getMessage());
            $this->logException($exception);
        }
    }
}
