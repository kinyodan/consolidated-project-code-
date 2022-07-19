<?php
namespace App\Console\Commands\Search;

use App\Http\Controllers\Helpers\ManageSearchEngineDataHelper;
use App\Http\Controllers\Traits\CanLog;
use Exception;
use Illuminate\Console\Command;

class ClearCoursesIndex extends Command
{
    use CanLog;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'search:course:clear-index';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear the course index';

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
            if($this->confirm('Are you sure your want to clear the Courses Index?')){
                $this->info("Initializing the command.");

                (new ManageSearchEngineDataHelper())->clear();

                $this->info("Completed");
            }
        }catch (Exception $exception){
            $this->error($exception->getMessage());
        }
    }
}
