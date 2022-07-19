<?php
namespace App\Console\Commands\Search;

use App\Http\Controllers\Traits\CanLog;
use App\Jobs\UnpublishCourseFromSearchEngineJob;
use App\Models\Course;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class UnpublishPublishedCoursesCommand extends Command
{
    use CanLog;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'search:course:unpublish';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Unpublish published courses';

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
                ->where('should_unpublish', 1)
                ->where('is_picked_for_unpublishing', 0)
                ->get([
                    'course_code'
                ]);

            foreach ($courses as $course){
                DB::table((new Course())->getTable())
                    ->where('course_code', $course->course_code)
                    ->update([
                        'is_picked_for_unpublishing' => 1
                    ]);

                dispatch((new UnpublishCourseFromSearchEngineJob($course->course_code)))->onQueue('remove_course_to_search_engine');
            }
        }catch (\Exception $exception){
            $this->error($exception->getMessage());
        }
    }
}
