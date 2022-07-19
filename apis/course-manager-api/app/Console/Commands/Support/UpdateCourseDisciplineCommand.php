<?php
namespace App\Console\Commands\Support;

use App\Http\Controllers\Helpers\CraydelHelperFunctions;
use App\Http\Controllers\Traits\CanLog;
use App\Models\AcademicDiscipline;
use App\Models\Course;
use Illuminate\Support\Facades\DB;
use Illuminate\Console\Command;

class UpdateCourseDisciplineCommand extends Command
{
    use CanLog;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'support:update-course-discipline';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update the course discipline from current values to correct values.';

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
                ->whereNotIn('discipline_code', function ($query){
                    return $query->select('id')->from((new AcademicDiscipline())->getTable());
                })
                ->get([
                    'id',
                    'discipline_code'
                ]);

            if(count($courses) > 0){
                $this->info(count($courses). ' found.');
                foreach ($courses as $course){
                    DB::transaction(function () use($course){
                        if(isset($course->discipline_code) && !empty($course->discipline_code)){
                            $this->info('Current discipline code: '.$course->discipline_code);

                            $discipline_id = DB::table((new AcademicDiscipline())->getTable())
                                ->where('discipline_code', trim($course->discipline_code))
                                ->value('id');

                            if(empty($discipline_id)){
                                $this->info('Searching based on academic discipline name');

                                $discipline_id = DB::table((new AcademicDiscipline())->getTable())
                                    ->where('discipline_name', CraydelHelperFunctions::toCleanString($course->discipline_code))
                                    ->value('id');
                            }

                            if($discipline_id){
                                DB::table((new Course())->getTable())
                                    ->where('id', $course->id)
                                    ->update([
                                        'discipline_code' => $discipline_id,
                                        'has_updates' => 1,
                                        'is_picked_for_indexing' => 0,
                                        'time_picked_for_indexing' => null,
                                        'time_taken_to_index' => null,
                                        'indexing_error' => null
                                    ]);

                                $this->info('Updated to: '.$discipline_id);
                            }else{
                                $this->info('Discipline id not found.');
                            }
                        }
                    });
                }
            }else{
                $this->info('No courses which miss matched course found.');
            }
        }catch (\Exception $exception){
            $this->error($exception->getMessage());
            $this->logException($exception);
        }
    }
}
