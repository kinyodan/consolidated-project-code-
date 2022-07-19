<?php
namespace App\Jobs;

use App\Http\Controllers\Courses\CourseController;
use Exception;

class DeleteImageCDNJob extends Job
{
    /**
     * @var $course_code
    */
    protected $course_code;

    /**
     * Create a new job instance.
     *
     * @param string $course_code
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
            CourseController::deleteInstitutionLogoImages(
                $this->course_code
            );
        }
    }
}
