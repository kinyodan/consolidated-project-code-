<?php
namespace App\Console\Commands\Search;

use App\Http\Controllers\Traits\CanLog;
use App\Jobs\PushCourseDataToSearchEngineJob;
use App\Models\Course;
use App\Models\CourseSearchIndexList;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class BuildCoursesSearchCommand extends Command
{
    use CanLog;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'search:course:build';

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
            DB::table((new CourseSearchIndexList())->getTable())
                ->select('course_code')
                ->distinct()
                ->where('is_deleted', 0)
                ->where('has_updates', 1)
                ->where('is_active', 1)
                ->where('is_picked_for_indexing', 0)
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
                                'is_picked_for_indexing' => 1,
                                'time_picked_for_indexing' => Carbon::now()->toDateTimeString()
                            ]);

                        dispatch((new PushCourseDataToSearchEngineJob($course->course_code)))->onQueue('push_course_to_search_engine');
                    }
                });
        }catch (\Exception $exception){
            $this->error($exception->getMessage());
        }
    }
}
