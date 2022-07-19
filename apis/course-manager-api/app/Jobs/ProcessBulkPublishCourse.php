<?php

namespace App\Jobs;

use App\Http\Controllers\Courses\Commands\PublishCourseCommandController;

class ProcessBulkPublishCourse extends Job
{

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
            PublishCourseCommandController::publishSelectedCourse(
                $this->course_code,
                $this->batch_no,
            );
        }
    }
}
