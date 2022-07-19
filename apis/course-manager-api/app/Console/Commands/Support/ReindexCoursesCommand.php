<?php
namespace App\Console\Commands\Support;

use App\Http\Controllers\Traits\CanLog;
use App\Models\Course;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ReindexCoursesCommand extends Command
{
    use CanLog;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'support:courses:re-index';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reindex courses';

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
                ->where('is_active', 1)
                ->get([
                    'id',
                    'course_code'
                ]);

            $bar = $this->output->createProgressBar(count($courses));
            $bar->start();

            foreach ($courses as $course){
                $this->update($course);

                $bar->advance();
            }

            $bar->finish();
        }catch (Exception $exception){
            $this->error($exception->getMessage());
        }
    }

    /**
     * Update the course currency
     *
     * @param object|null $course
     * @return false|int
     */
    protected function update(?object $course){
        try{
            $update = false;

            DB::transaction(function () use($course, &$update){
                $update = DB::table((new Course())->getTable())
                    ->where('course_code', $course->course_code)
                    ->update([
                        'has_updates' => 1,
                        'is_picked_for_indexing' => 0,
                        'time_picked_for_indexing' => null,
                        'time_taken_to_index' => null,
                        'indexing_error' => null
                    ]);
            });

            return $update;
        }catch (\Exception $exception){
            $this->info($exception->getMessage());

            return false;
        }
    }
}
