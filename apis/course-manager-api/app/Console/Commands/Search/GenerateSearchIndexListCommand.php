<?php
namespace App\Console\Commands\Search;

use App\Http\Controllers\Courses\Commands\GenerateCourseSearchIndexListCommandController;
use App\Http\Controllers\Traits\CanLog;
use App\Models\Course;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class GenerateSearchIndexListCommand extends Command
{
    use CanLog;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'search:course:generate-index-list';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate the course index list';

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
            $courses = DB::table((new Course())->getTable())
                ->where('is_deleted', 0)
                ->where('has_updates', 1)
                ->where('is_active', 1)
                ->where('is_picked_for_indexing', 0)
                ->whereNotNull('course_small_image')
                ->whereNotNull('course_image')
                ->get();

            $this->info("Course found ".count($courses));

            foreach ($courses as $course){
                $this->info('Generating course search index list for: '.$course->course_code);
                GenerateCourseSearchIndexListCommandController::generate(
                    $course->course_code
                );
            }
        }catch (\Exception $exception){
            $this->logException($exception);
            $this->error($exception->getMessage());
        }
    }
}
