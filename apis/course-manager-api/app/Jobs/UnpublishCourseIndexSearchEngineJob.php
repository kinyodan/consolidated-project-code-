<?php
namespace App\Jobs;

use App\Http\Controllers\Courses\Commands\PushCourseDataToSearchEngineCommandController;
use App\Http\Controllers\Traits\CanLog;
use Exception;

class UnpublishCourseIndexSearchEngineJob extends Job
{
    use CanLog;

    /**
     * @var string|null $course_index
    */
    protected ?string $course_code;

    /**
     * Create a new job instance.
     *
     * @param $course_index
     *
     */
    public function __construct($course_code)
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
        if(!empty($this->course_index)){
            (new PushCourseDataToSearchEngineCommandController($this->course_code))
                ->make()
                ->delete();
        }
    }
}
