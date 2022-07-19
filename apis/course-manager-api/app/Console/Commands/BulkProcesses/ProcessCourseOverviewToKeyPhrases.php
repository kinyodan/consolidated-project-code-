<?php

namespace App\Console\Commands\BulkProcesses;

use App\Http\Controllers\KeyPhrases\Commands\CreateKeyPhrasesCommandController;
use App\Http\Controllers\Traits\CanRespond;
use App\Jobs\PushCourseDataToInternalSearchEngineJob;
use App\Jobs\PushCourseDataToKeyPhrasesExtractorJob;
use App\Jobs\PushSelectedKeyPhrasesToSearchInternalSearchEngineJob;
use App\Models\CourseSearchIndexList;
use Carbon\Carbon;
use Illuminate\Console\Command;
use App\Models\Course;
use App\Http\Controllers\Traits\CanLog;
use App\Helpers\CraydelJSONResponseType;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProcessCourseOverviewToKeyPhrases extends Command
{

    use CanLog, CanRespond;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bulk:process-course-overview-to-key-phrases';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process course overview to key phrases';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }
    
    public function handle()
    {

        try {
  
          $courses =DB::table((new CourseSearchIndexList())->getTable())
            ->select('course_code','course_overview')
            ->distinct()
            ->where('is_deleted', 0)
            ->where('is_active', 1)
            ->where('is_picked_for_phrases_selection', 0)
            ->orderBy('id')
            ->chunk(config('craydle.default_data_chunk_size'), function ($courses){
              $this->info("Courses found " . count($courses));
      
              foreach ($courses as $course){
                          $this->info('Pushing course data into key phrase extractions : '.$course->course_code);
        
                DB::table((new CourseSearchIndexList())->getTable())
                  ->where('course_code', $course->course_code)
                  ->update([
                    'is_picked_for_phrases_selection' => 1,
                    'updated_at' => Carbon::now()->toDateTimeString()
                  ]);
                dispatch((new PushCourseDataToKeyPhrasesExtractorJob($course)))->onQueue('push_course_to_key_phrase_extractor');
              }
        
                });
        } catch (\Exception $exception){
           $this->error($exception->getMessage());
        }
    }
}
