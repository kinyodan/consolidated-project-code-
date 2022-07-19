<?php

namespace App\Jobs;
use App\Http\Controllers\Courses\Commands\BulkDeleteCourseCommandController;
use App\Http\Controllers\Courses\CourseController;
use App\Http\Controllers\Traits\CanLog;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Courses\Commands\DeleteCourseCommandController;
class ProcessBulkDeleteCourse extends Job
{
use CanLog;
    protected $course_code;
    protected  $batch_no;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($course_code,$batch_no)
    {
        $this->course_code = $course_code;
        $this->batch_no = $batch_no;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        if(!empty($this->course_code &&  $this->batch_no )){
            DeleteCourseCommandController::deleteSelectedCourse(
                $this->course_code,
                $this->batch_no,
            );
        }

    }
}
