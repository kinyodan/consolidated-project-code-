<?php
namespace App\Http\Controllers\Application\Commands\Workflow\Opportunity;

use App\Http\Controllers\Application\Commands\Workflow\Opportunity\Commands\OnOpportunityApplicationDoneCommandController;
use App\Http\Controllers\Application\Commands\Workflow\Opportunity\Commands\OnOpportunityCourseDecidedCommandController;
use App\Http\Controllers\Application\Commands\Workflow\Opportunity\Commands\OnOpportunityDepositPaidCommandController;
use App\Http\Controllers\Application\Commands\Workflow\Opportunity\Commands\OnOpportunityDocumentCompletedCommandController;
use App\Http\Controllers\Application\Commands\Workflow\Opportunity\Commands\OnOpportunityOfferAcceptedCommandController;
use App\Http\Controllers\Application\Commands\Workflow\Opportunity\Commands\OnOpportunityOfferReceivedCommandController;
use App\Http\Controllers\Application\Commands\Workflow\Opportunity\Commands\OnOpportunityStudentEnrolledCommandController;
use App\Http\Controllers\Application\Commands\Workflow\Opportunity\Commands\OnOpportunityVisaApplicationSubmittedCommandController;
use App\Http\Controllers\Application\Commands\Workflow\Opportunity\Commands\OnOpportunityVisaIssuedCommandController;
use App\Http\Controllers\Helpers\CraydelHelperFunctions;
use App\Http\Controllers\Providers\LeadManagement\LeadManagementController;
use App\Http\Controllers\Traits\CanLog;
use App\Jobs\PushLeadAndOpportunitiesDataToDateLakeJob;
use App\Jobs\SendNotificationSchoolServiceJob;
use App\Models\CourseApplicationStageTracker;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class OpportunityWorkflowCommandController
{
    use CanLog;

    /**
     * Receive the opportunity update
     *
     * @param Request $request
    */
    public function receive(Request $request){
        try{
            $opportunity_id = $request->input('opportunity_id');
            $opportunity_stage = $request->input('opportunity_stage');

            if(empty($opportunity_id)){
                throw new Exception("Invalid opportunity ID while processing the opportunity update");
            }

            if(empty($opportunity_stage)){
                throw new Exception("Invalid opportunity stage while processing the opportunity update");
            }

            dispatch(new PushLeadAndOpportunitiesDataToDateLakeJob(
                'ET818244968493',
                json_encode(['opportunity_id' => $opportunity_id, 'opportunity_stage' => $opportunity_stage])
            ))->onQueue('push_event');

            DB::transaction(function () use($opportunity_id, $opportunity_stage){
                DB::table((new CourseApplicationStageTracker())->getTable())
                    ->insertOrIgnore([
                        'opportunity_id' => $opportunity_id,
                        'current_stage' => trim($opportunity_stage),
                        'student_notification_sent' => 0,
                        'created_at' => Carbon::now()->toDateTimeString()
                    ]);
            });
        }catch (Exception | Throwable $exception){
            $this->logException($exception);
        }
    }

    /**
     * Process opportunity updates
     *
     * @param string|null $opportunity_id
     * @param string|null $opportunity_stage
     */
    public static function process(?string $opportunity_id, ?string $opportunity_stage){
        try{
            $opportunity_details = LeadManagementController::getOpportunityDetailsFromCRM($opportunity_id);

            if(!$opportunity_details->status){
                throw new Exception(isset($opportunity_details->message) && !empty($opportunity_details->message) ? $opportunity_details->message : "Unable to fetch the opportunity details for opportunity ID : ".$opportunity_id);
            }

            if(!isset($opportunity_details->data->id) || empty($opportunity_details->data->id)){
                throw new Exception("Incomplete opportunity details fetched. Try again");
            }

            $opportunity = $opportunity_details->data ?? null;
            $opportunity_contact_id = isset($opportunity->Contact_Name->id) && !empty($opportunity->Contact_Name->id) ? $opportunity->Contact_Name->id : null;

            $contact = LeadManagementController::getCustomerDetailsFromCRM($opportunity_contact_id);
            $contact_email = isset($contact->data->Email) ? CraydelHelperFunctions::toEmailAddress($contact->data->Email) : null;

            if(!empty($contact_email)){
                $course_name = $opportunity->Course_Name ?? null;
                $institution_name = $opportunity->Institution_Name ?? null;
                $study_destination = $opportunity->Study_Destination ?? null;
                $which_Intake = $opportunity->Which_Intake ?? null;

                dispatch(new SendNotificationSchoolServiceJob(
                    $contact_email,
                    $opportunity_id,
                    $opportunity_stage,
                    $institution_name,
                    $study_destination,
                    $which_Intake,
                    $course_name
                ))->onQueue('push_event');
            }

            if($opportunity_stage == 'Course Decided'){
                OnOpportunityCourseDecidedCommandController::process($opportunity_details->data);
            }elseif ($opportunity_stage == 'Document Completed'){
                OnOpportunityDocumentCompletedCommandController::process($opportunity_details->data);
            }elseif ($opportunity_stage == 'Application Done'){
                OnOpportunityApplicationDoneCommandController::process($opportunity_details->data);
            }elseif ($opportunity_stage == 'Offer Received'){
                OnOpportunityOfferReceivedCommandController::process($opportunity_details->data);
            }elseif ($opportunity_stage == 'Offer Accepted'){
                OnOpportunityOfferAcceptedCommandController::process($opportunity_details->data);
            }elseif ($opportunity_stage == 'Deposit Paid'){
                OnOpportunityDepositPaidCommandController::process($opportunity_details->data);
            }elseif ($opportunity_stage == 'Visa Application Submitted'){
                OnOpportunityVisaApplicationSubmittedCommandController::process($opportunity_details->data);
            }elseif ($opportunity_stage == 'Visa Issued'){
                OnOpportunityVisaIssuedCommandController::process($opportunity_details->data);
            }elseif ($opportunity_stage == 'Student Enrolled'){
                OnOpportunityStudentEnrolledCommandController::process($opportunity_details->data);
            }
        }catch (Exception | Throwable $exception){
            (new self())->logException($exception);
        }
    }
}
