<?php
namespace App\Jobs;

use App\Http\Controllers\Courses\Commands\PushCourseDataToInternalSearchEngineCommandController;
use App\Http\Controllers\Courses\Commands\PushCourseDataToSearchEngineCommandController;
use App\Http\Controllers\KeyPhrases\Commands\CreateKeyPhrasesCommandController;
use Exception;

class PushSelectedKeyPhrasesToSearchInternalSearchEngineJob extends Job
{
  
    protected ?object $phrase;

    /**
     * Create a new job instance.
     *
     * @param ?object $phrase
     *
     */
    public function __construct(object $phrase)
    {
        $this->phrase = $phrase;
    }

    /**
     * Execute the job.
     *
     * @return void
     * @throws Exception
     */
    public function handle()
    {
        if(!empty($this->phrase)){
  
          CreateKeyPhrasesCommandController::pushExtractedKeyPhrases($this->phrase);
          
        }
    }
}
