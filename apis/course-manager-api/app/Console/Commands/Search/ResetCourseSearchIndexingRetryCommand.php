<?php
namespace App\Console\Commands\Search;

use App\Http\Controllers\Helpers\ManageSearchEngineDataHelper;
use App\Http\Controllers\Traits\CanLog;
use App\Models\Course;
use App\Models\CourseSearchIndexList;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ResetCourseSearchIndexingRetryCommand extends Command
{
    use CanLog;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'search:courses:reset-index';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset the courses indexing to allow reindexing.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle() {
        try{
            if($this->confirm('Are you sure you want to clear the courses index')){
                (new ManageSearchEngineDataHelper())->clear();

                DB::table((new CourseSearchIndexList())->getTable())
                    ->where('is_deleted', 0)
                    ->where('has_updates', 1)
                    ->where('is_active', 1)
                    ->where('is_published', 1)
                    ->where('is_picked_for_indexing', 0)
                    ->whereNotNull('course_small_image')
                    ->whereNotNull('course_image')
                    ->update([
                        'has_updates' => 1,
                        'is_picked_for_indexing' => 0,
                        'time_picked_for_indexing' => null,
                        'time_taken_to_index' => null,
                        'indexing_error' => null
                    ]);

                $this->info('Index reset, awaiting the indexing job to run.');
            }else{
                $this->info('Course index reset canceled');
            }
        }catch (\Exception $exception){
            $this->error($exception->getMessage());
        }
    }
}
