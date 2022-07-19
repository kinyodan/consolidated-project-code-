<?php
namespace App\Console\Commands\BulkProcesses;

use App\Http\Controllers\Traits\CanUploadImage;
use App\Jobs\UploadImageToCDNJob;
use App\Models\Course;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class RetryFailedCoursesImageProcessingCommand extends Command
{
    use CanUploadImage;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bulk:retry:courses-image-processing';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Retry courses image processing.';

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
            /*$courses = DB::table((new Course())->getTable())
                ->whereNotNull('temp_course_image_url')
                ->get([
                    'country_code'
                ]);

            foreach ($courses as $course){
                if(isset($course->country_code)){
                    dispatch((new UploadImageToCDNJob($course->country_code)))
                        ->onQueue('upload_images_to_cdn');
                }
            }*/
        }catch (\Exception $exception){
            $this->error($exception->getMessage());
        }
    }
}
