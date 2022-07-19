<?php
namespace App\Console\Commands\BulkProcesses;
use App\Http\Controllers\Courses\Commands\BulkDeleteCourseCommandController;
use Illuminate\Console\Command;
use App\Models\BulkDelete;
use App\Http\Controllers\Traits\CanLog;
use App\Helpers\CraydelJSONResponseType;

class BulkDeleteCourse extends Command
{

    use CanLog;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bulk:delete-courses';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Bulk Delete Courses';

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
     * @return mixed
     */
    public function handle()
    {

        try{
            BulkDelete::where('is_processed', 0)
                ->where('picked_for_processing', 0)
                ->where('action_type','=','delete')
                ->chunk(10, function ($courses) {
                    foreach ($courses as $course) {
                        BulkDeleteCourseCommandController::selectedCourse($course);
                    }
                });
        }catch ( \Exception $exception){
            self::craydelExceptionLogger($exception);
            return self::respondInJSON(new CraydelJSONResponseType(
                false,
                $exception->getMessage()
            ));
        }
    }
}
