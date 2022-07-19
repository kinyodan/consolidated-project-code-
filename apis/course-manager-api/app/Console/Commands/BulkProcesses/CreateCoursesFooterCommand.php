<?php
namespace App\Console\Commands\BulkProcesses;

use App\Http\Controllers\Courses\Commands\CreateCoursesFooterCommandController;
use App\Http\Controllers\Traits\CanLog;
use Illuminate\Console\Command;

class CreateCoursesFooterCommand extends Command
{
    use CanLog;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bulk:courses:create-footer';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create courses footer.';

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
            $this->logMessage('Starting to process the courses footer section.');
            (new CreateCoursesFooterCommandController())->make();
            $this->logMessage('Finished processing the courses footer section.');
        }catch (\Exception $exception){
            $this->logException($exception);
        }
    }
}
