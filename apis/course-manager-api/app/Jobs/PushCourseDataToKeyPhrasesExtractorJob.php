<?php
namespace App\Jobs;

use App\Http\Controllers\Courses\Commands\PushCourseDataToInternalSearchEngineCommandController;
use App\Http\Controllers\Courses\Commands\PushCourseDataToSearchEngineCommandController;
use App\Http\Controllers\KeyPhrases\Commands\CreateKeyPhrasesCommandController;
use Exception;

class PushCourseDataToKeyPhrasesExtractorJob extends Job
{
  
  
  
  private ?object $course;
  
  public function __construct($course)
    {
        $this->course = $course;
    }

    /**
     * Execute the job.
     *
     * @return void
     * @throws Exception
     */
    public function handle()
    {
        if(!empty($this->course)){
          CreateKeyPhrasesCommandController::selectedCourse($this->course);
          
        }
    }
}
