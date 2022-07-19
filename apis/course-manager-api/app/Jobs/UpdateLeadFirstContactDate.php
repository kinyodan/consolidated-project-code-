<?php
namespace App\Jobs;

use App\Http\Controllers\Providers\LeadManagement\LeadManagementController;
use Exception;

class UpdateLeadFirstContactDate extends Job
{
    /**
     * @var string $lms_provider_lead_id
    */
    public string $lms_provider_lead_id;

    /**
     * @var array|null $lead_details
    */
    public ?array $lead_details;

    /**
     * Create a new job instance.
     *
     * @param string|null $lms_provider_lead_id
     * @param array|null $lead_details
     */
    public function __construct(?string $lms_provider_lead_id, ?array $lead_details)
    {
        $this->lms_provider_lead_id = $lms_provider_lead_id;
        $this->lead_details = $lead_details;
    }

    /**
     * Execute the job.
     *
     * @return void
     * @throws Exception
     */
    public function handle()
    {
        if(!empty($this->lms_provider_lead_id)){
            LeadManagementController::updateLeadDetailsOnCRM(
                $this->lms_provider_lead_id,
                $this->lead_details
            );
        }
    }
}
