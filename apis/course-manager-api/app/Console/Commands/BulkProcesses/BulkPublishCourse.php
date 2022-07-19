<?php

namespace App\Console\Commands\BulkProcesses;

use App\Helpers\CraydelJSONResponseType;
use App\Http\Controllers\Courses\Commands\BulkDeleteCourseCommandController;
use App\Http\Controllers\Courses\Commands\BulkPublishCourseCommandController;
use App\Http\Controllers\Courses\Commands\BulkUnPublishCourseCommandController;
use App\Models\BulkDelete;
use Illuminate\Console\Command;

class BulkPublishCourse extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bulk:publish-courses';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Bulk Publish Courses';

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
                ->where('action_type','=','publish')
                ->chunk(10, function ($courses) {
                    foreach ($courses as $course) {
                        BulkUnPublishCourseCommandController::selectedCourse($course);
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
