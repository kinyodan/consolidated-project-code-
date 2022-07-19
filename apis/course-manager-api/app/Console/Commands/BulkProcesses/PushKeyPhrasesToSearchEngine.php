<?php

namespace App\Console\Commands\BulkProcesses;

use App\Http\Controllers\KeyPhrases\Commands\CreateKeyPhrasesCommandController;
use App\Http\Controllers\Traits\CanRespond;
use App\Jobs\PushSelectedKeyPhrasesToSearchInternalSearchEngineJob;
use App\Models\CourseSearchIndexList;
use App\Models\SelectedKeyphrase;
use Carbon\Carbon;
use Illuminate\Console\Command;
use App\Models\Course;
use App\Http\Controllers\Traits\CanLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PushKeyPhrasesToSearchEngine extends Command
{
  
  use CanLog, CanRespond;
  
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'bulk:push-key-phrases-to-search-engine';
  
  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Push Key Phrases to search engine';
  
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
      SelectedKeyphrase::where('is_pushed_to_search_engine', 0)
        ->chunk(config('craydle.default_data_chunk_size'), function ($phrases) {
          foreach ($phrases as $phrase) {
            $this->info('Pushing selected KeyPhrases to internal search engine: '.$phrase);
  
            DB::table((new SelectedKeyphrase())->getTable())
              ->where('course_code', $phrase->course_code)
              ->update([
                'is_pushed_to_search_engine' => 1,
                'updated_at' => Carbon::now()->toDateTimeString()
              ]);
            
            dispatch((new PushSelectedKeyPhrasesToSearchInternalSearchEngineJob($phrase)))->onQueue('push_selected_key_phrase_to_internal_search_engine');
          }
        });
      
    } catch (\Exception $exception) {
      $this->error($exception->getMessage());
    }
  }
}
