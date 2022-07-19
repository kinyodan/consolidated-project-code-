<?php
namespace App\Jobs;

use App\Http\Controllers\Courses\CourseController;
use App\Models\Course;
use Exception;
use Illuminate\Support\Facades\DB;

class UploadImageToCDNJob extends Job
{
    /**
     * @var string $course_code
    */
    protected string $course_code;

    /**
     * Create a new job instance.
     *
     * @param string $course_code
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
            $course = DB::table((new Course())->getTable())
                ->where('course_code', trim($this->course_code))
                ->first([
                    'temp_course_image_url'
                ]);

            if(isset($course->temp_course_image_url) && !empty($course->temp_course_image_url)){
                CourseController::uploadCourseImage(
                    $this->course_code,
                    $course->temp_course_image_url
                );
            }
        }
    }
}
