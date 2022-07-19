<?php
namespace App\Listeners;

use App\Events\CourseApplicationLeadSubmittedToCRMEvent;
use App\Http\Controllers\Application\Commands\Workflow\Leads\LeadWorkflowCommandController;
use App\Http\Controllers\Traits\CanCache;
use App\Http\Controllers\Traits\CanLog;
use App\Jobs\PushLeadAndOpportunitiesDataToDateLakeJob;
use Exception;
use Illuminate\Events\Dispatcher;
use Throwable;

class CourseApplicationWorkflowListener
{
    use CanLog, CanCache;

    /**
     * Course application has been submitted to the CRM
     *
     * @param CourseApplicationLeadSubmittedToCRMEvent $event
     * @return void
     */
    public function onCourseApplicationLeadSubmittedToCRM(CourseApplicationLeadSubmittedToCRMEvent $event){
        try{
            $this->logMessage('Course Application Lead Submitted To CRM Event: '.$event->crm_lead_id);

            dispatch(new PushLeadAndOpportunitiesDataToDateLakeJob(
                'ET875165944178',
                json_encode(['lead_id' => $event->crm_lead_id])
            ))->onQueue('push_event');

            LeadWorkflowCommandController::sendWelcomeEmailWhenALeadHasBeenSubmittedToCRM(
                $event->crm_lead_id
            );
        }catch (Exception | Throwable $exception){
            $this->logException($exception);
        }
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param Dispatcher $events
     * @return void
     */
    public function subscribe(Dispatcher $events)
    {
        $events->listen(
            CourseApplicationLeadSubmittedToCRMEvent::class,
            [CourseApplicationWorkflowListener::class, 'onCourseApplicationLeadSubmittedToCRM']
        );
    }
}
