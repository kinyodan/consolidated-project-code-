<?php
namespace App\Console\Commands\Support;

use App\Http\Controllers\Courses\Commands\UpdateCourseStaticsCommandController;
use App\Http\Controllers\Traits\CanLog;
use App\Models\Leads;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class PopulateCourseLeadAndEnrollmentStaticsFromPreviousLeadsDataCommand extends Command
{
    use CanLog;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'support:courses:populate-lead-enrollment-statics-from-previous-leads-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Populate leads and enrollment statics from previous leads data';

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
            $leads = DB::table((new Leads())->getTable())
                ->whereNotNull('course_code')
                ->whereNotNull('lms_provider_lead_id')
                ->get([
                    'lms_provider_lead_id'
                ]);

            $bar = $this->output->createProgressBar(count($leads));
            $bar->start();

            foreach ($leads as $lead){
                (new UpdateCourseStaticsCommandController())->updateLeadCountAfterLeadIsSubmittedToCRM(
                    $lead->lms_provider_lead_id
                );

                $bar->advance();
            }

            $bar->finish();
        }catch (Exception $exception){
            $this->error($exception->getMessage());
        }
    }
}
