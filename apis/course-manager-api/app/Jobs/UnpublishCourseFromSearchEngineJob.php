<?php
namespace App\Jobs;

use App\Http\Controllers\Courses\Commands\PushCourseDataToSearchEngineCommandController;
use Exception;
use Illuminate\Support\Facades\Log;

class UnpublishCourseFromSearchEngineJob extends Job
{
    /**
     * @var string|null $course_code
    */
    public ?string $course_code;

    /**
     * @var bool $should_permanently_delete
    */
    public bool $should_permanently_delete;

    /**
     * Create a new job instance.
     *
     * @param string $course_code
     * @param bool $should_permanently_delete
     */
    public function __construct(string $course_code, bool $should_permanently_delete = false)
    {
        $this->course_code = $course_code;
        $this->should_permanently_delete = $should_permanently_delete;
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
                ->delete($this->should_permanently_delete ?? false);
        }
    }
}
