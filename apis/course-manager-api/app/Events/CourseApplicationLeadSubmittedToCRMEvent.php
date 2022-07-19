<?php
namespace App\Events;

use Illuminate\Queue\SerializesModels;

class CourseApplicationLeadSubmittedToCRMEvent extends Event
{
    use SerializesModels;

    /**
     * @var string|null $crm_lead_id
    */
    public ?string $crm_lead_id;

    /**
     * Create a new event instance.
     *
     * @param string|null $crm_lead_id
     */
    public function __construct(?string $crm_lead_id)
    {
        $this->crm_lead_id = $crm_lead_id;
    }
}
