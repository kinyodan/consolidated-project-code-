<?php

namespace App\Console\Commands\BulkProcesses;

use App\Helpers\CraydelJSONResponseType;
use App\Http\Controllers\Courses\Commands\BulkPublishCourseCommandController;
use App\Models\BulkDelete;
use Illuminate\Console\Command;

class BulkUnpublishCourse extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bulk:unpublish-courses';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Bulk unPublish Courses';

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
                ->where('action_type','=','unpublish')
                ->chunk(10, function ($courses) {
                    foreach ($courses as $course) {
                        BulkPublishCourseCommandController::selectedCourse($course);
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
