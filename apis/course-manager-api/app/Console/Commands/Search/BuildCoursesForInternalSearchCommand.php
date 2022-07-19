<?php
namespace App\Console\Commands\Search;

use App\Http\Controllers\Courses\Commands\PushCourseDataToInternalSearchEngineCommandController;
use App\Http\Controllers\Traits\CanLog;
use App\Jobs\PushCourseDataToInternalSearchEngineJob;
use App\Jobs\PushCourseDataToSearchEngineJob;
use App\Models\Course;
use App\Models\CourseSearchIndexList;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class BuildCoursesForInternalSearchCommand extends Command
{
    use CanLog;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'search:course:internal_build';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Build courses search index';

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
            $courses =DB::table((new CourseSearchIndexList())->getTable())
                ->select('course_code')
                ->distinct()
                ->where('is_deleted', 0)
                ->where('is_active', 1)
                ->where('is_pushed_to_search_engine', 0)
                ->whereNotNull('course_small_image')
                ->whereNotNull('course_image')
                ->orderBy('id')
                ->chunk(config('craydle.default_data_chunk_size'), function ($courses){
                    $this->info("Courses found " . count($courses));

                    foreach ($courses as $course){
                        $this->info('Pushing course data into index : '.$course->course_code);

                        DB::table((new CourseSearchIndexList())->getTable())
                            ->where('course_code', $course->course_code)
                            ->update([
                                'is_pushed_to_search_engine' => 1,
                                'time_picked_for_pushing_to_search_engine' => Carbon::now()->toDateTimeString()
                            ]);
                      dispatch((new PushCourseDataToInternalSearchEngineJob($course->course_code)))->onQueue('push_course_to_internal_search_engine');
                    }
                });
        }catch (\Exception $exception){
            $this->error($exception->getMessage());
        }
    }
}
