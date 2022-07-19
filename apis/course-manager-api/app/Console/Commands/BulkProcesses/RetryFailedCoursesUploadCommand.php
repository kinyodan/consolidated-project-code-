<?php
namespace App\Console\Commands\BulkProcesses;

use App\Http\Controllers\Courses\Commands\ImportCourseCommandController;
use App\Http\Controllers\Courses\CourseController;
use App\Http\Controllers\Traits\CanUploadImage;
use App\Models\CourseBulkImport;
use Carbon\Carbon;
use Illuminate\Console\Command;

class RetryFailedCoursesUploadCommand extends Command
{
    use CanUploadImage;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bulk:retry:courses-upload';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Retry courses bulk upload.';

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
            $delayed = CourseBulkImport::where('is_processed', 0)
                ->where('created_at', '<', Carbon::now()->addMinutes(10))
                ->get();

            foreach ($delayed as $item){
                (new ImportCourseCommandController(
                    new CourseController()
                ))->process($item->id, json_decode($item->file_data));
            }
        }catch (\Exception $exception){
            $this->error($exception->getMessage());
        }
    }
}
