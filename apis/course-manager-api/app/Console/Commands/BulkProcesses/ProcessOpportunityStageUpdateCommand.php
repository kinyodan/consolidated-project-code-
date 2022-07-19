<?php
namespace App\Console\Commands\BulkProcesses;

use App\Http\Controllers\Traits\CanUploadImage;
use App\Jobs\ProcessOpportunityStageUpdateJob;
use App\Models\CourseApplicationStageTracker;
use Carbon\Carbon;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ProcessOpportunityStageUpdateCommand extends Command
{
    use CanUploadImage;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bulk:opportunities:process-staged-opportunity-stages';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process staged opportunities updates.';

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
            DB::table((new CourseApplicationStageTracker())->getTable())
                ->where('is_processed', 0)
                ->where('is_picked_for_processing', 0)
                ->orderBy('created_at')
                ->chunk(10, function($updates){
                    foreach ($updates as $update){
                        $opportunity_id = !empty($update->opportunity_id) ? trim($update->opportunity_id) : null;
                        $opportunity_stage = !empty($update->current_stage) ? trim($update->current_stage) : null;

                        DB::table((new CourseApplicationStageTracker())->getTable())
                            ->where('id', $update->id)
                            ->update([
                                'is_processed' => 1,
                                'is_picked_for_processing' => 1,
                                'updated_at' => Carbon::now()->toDateTimeString()
                            ]);

                        dispatch(new ProcessOpportunityStageUpdateJob(
                            $opportunity_id,
                            $opportunity_stage
                        ))->onQueue('course_application_workflow');
                    }
                });
        }catch (Exception $exception){
            $this->error($exception->getMessage());
        }
    }
}
