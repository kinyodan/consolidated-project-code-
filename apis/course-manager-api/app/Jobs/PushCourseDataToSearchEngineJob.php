<?php
namespace App\Jobs;

use App\Http\Controllers\Courses\Commands\PushCourseDataToSearchEngineCommandController;
use Exception;

class PushCourseDataToSearchEngineJob extends Job
{
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
            (new PushCourseDataToSearchEngineCommandController($this->course_code))
                ->make()
                ->push();
        }
    }
}
