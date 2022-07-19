<?php
namespace App\Console\Commands\Search;

use App\Http\Controllers\Traits\CanLog;
use App\Http\Controllers\Traits\CanUploadImage;
use Illuminate\Console\Command;

class RetryInstitutionSearchIndexingCommand extends Command
{
    use CanLog;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'search:course:retry';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Retry course indexing';
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

        }catch (\Exception $exception){
            $this->error($exception->getMessage());
        }
    }
}
