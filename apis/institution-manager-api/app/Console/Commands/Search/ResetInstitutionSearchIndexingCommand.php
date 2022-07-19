<?php
namespace App\Console\Commands\Search;

use App\Http\Controllers\Helpers\ManageSearchEngineDataHelper;
use App\Http\Controllers\Traits\CanLog;
use App\Models\Institution;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ResetInstitutionSearchIndexingCommand extends Command
{
    use CanLog;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'search:institutions:reset-index';

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
            if($this->confirm('Are you sure you want to clear the institutions index')){
                (new ManageSearchEngineDataHelper())->clear();

                DB::table((new Institution())->getTable())
                    ->update([
                        'has_updates' => 1,
                        'is_picked_for_indexing' => 0,
                        'time_picked_for_indexing' => null,
                        'time_taken_to_index' => null,
                        'indexing_error' => null
                    ]);

                $this->info('Index reset, awaiting the indexing job to run.');
            }else{
                $this->info('Institution index reset canceled');
            }
        }catch (\Exception $exception){
            $this->error($exception->getMessage());
        }
    }
}
