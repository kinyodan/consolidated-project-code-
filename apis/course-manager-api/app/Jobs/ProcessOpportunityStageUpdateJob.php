<?php
namespace App\Jobs;

use App\Http\Controllers\Application\Commands\Workflow\Opportunity\OpportunityWorkflowCommandController;
use App\Http\Controllers\Traits\CanLog;

class ProcessOpportunityStageUpdateJob extends Job
{
    use CanLog;

    /**
     * @var string|null $opportunity_id
     */
    protected ?string $opportunity_id;

    /**
     * @var string|null $opportunity_stage
    */
    protected ?string $opportunity_stage;

    /**
     * Create a new job instance.
     *
     * @param string|null $opportunity_id
     * @param string|null $opportunity_stage
     */
    public function __construct(?string $opportunity_id, ?string $opportunity_stage){
        $this->opportunity_id = $opportunity_id;
        $this->opportunity_stage = $opportunity_stage;
    }

    /**
     * Process the imported courses
    */
    public function handle()
    {
        try{
            OpportunityWorkflowCommandController::process(
                $this->opportunity_id,
                $this->opportunity_stage
            );
        }catch (\Exception $exception){
            $this->logException($exception);
        }
    }
}
