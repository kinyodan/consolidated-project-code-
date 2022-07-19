<?php
namespace App\Console\Commands\Support;

use App\Http\Controllers\Traits\CanLog;
use App\Models\Course;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class GeneratePopularityIndexCommand extends Command
{
    use CanLog;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'support:generate-popularity-index';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create bulk import template';

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
        try {
            $courses = DB::table((new Course())->getTable())
                ->where('is_deleted', 0)
                ->where('is_active', 1)
                ->get([
                    'course_code'
                ]);

            foreach ($courses as $course){
                DB::table((new Course())->getTable())
                    ->where('course_code', $course->course_code)
                    ->update([
                        'popularity' => DB::raw('ROUND(RAND(), 6)'),
                        'has_updates' => 1,
                        'is_picked_for_indexing' => 0,
                        'time_picked_for_indexing' => null
                    ]);
            }
        }catch (\Exception $exception){
            $this->error($exception->getMessage());
            $this->logException($exception);
        }
    }
}
