<?php
namespace App\Console\Commands\BulkProcesses;

use App\Http\Controllers\Traits\CanLog;
use App\Models\Course;
use App\Models\CourseAcademicDiscipline;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class GenerateCourseAcademicCategoryLinkageFromExistingCoursesCommand extends Command
{
    use CanLog;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bulk:courses:generate-academic-category-linkage-from-existing-courses';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create courses footer.';

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
            DB::table((new Course())->getTable())
                ->where('course_academic_category_is_generated', 0)
                ->orderBy('id')
                ->chunk(config('craydle.default_data_chunk_size'), function ($courses){
                    DB::transaction(function () use($courses){
                        foreach ($courses as $course){
                            if(is_numeric($course->discipline_code)){
                                DB::table((new CourseAcademicDiscipline())->getTable())
                                    ->insertOrIgnore([
                                        'courses_id' => $course->id,
                                        'academic_disciplines_id' => $course->discipline_code
                                    ]);

                                DB::table((new Course())->getTable())
                                    ->where('id', $course->id)
                                    ->update([
                                        'course_academic_category_is_generated' => 1
                                    ]);
                            }
                        }
                    });
                });
        }catch (\Exception $exception){
            $this->logException($exception);
        }
    }
}
