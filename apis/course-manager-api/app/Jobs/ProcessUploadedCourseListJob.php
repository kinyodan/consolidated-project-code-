<?php
namespace App\Jobs;

use App\Http\Controllers\Courses\CourseController;
use App\Http\Controllers\Traits\CanLog;

class ProcessUploadedCourseListJob extends Job
{
    use CanLog;

    /**
     * Create a new job instance.
     *
     */
    public function __construct(){}

    /**
     * Process the imported courses
    */
    public function handle()
    {
        try{
            $this->logMessage('Start the job to process the uploaded courses');
            (new CourseController())->processImportedCourses();
        }catch (\Exception $exception){
            $this->logException($exception);
        }
    }
}
