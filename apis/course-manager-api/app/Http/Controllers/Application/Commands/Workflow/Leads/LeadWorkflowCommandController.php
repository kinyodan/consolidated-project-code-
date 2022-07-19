<?php
namespace App\Http\Controllers\Application\Commands\Workflow\Leads;

use App\Events\CourseApplicationLeadSubmittedToCRMEvent;
use App\Http\Controllers\Helpers\CraydelHelperFunctions;
use App\Http\Controllers\Helpers\LanguageTranslationHelper;
use App\Http\Controllers\Providers\LeadManagement\LeadManagementController;
use App\Http\Controllers\Traits\CanLog;
use App\Http\Controllers\Traits\CanSendEmail;
use App\Jobs\PushLeadAndOpportunitiesDataToDateLakeJob;
use App\Jobs\UpdateLeadFirstContactDate;
use App\Models\Leads;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class LeadWorkflowCommandController
{
    use CanLog, CanSendEmail;

    /**
     * Send lead welcome email
     *
     * @param string|null $crm_lead_id
     * @throws Throwable
     */
    public static function sendWelcomeEmailWhenALeadHasBeenSubmittedToCRM(?string $crm_lead_id){
        try{
            (new static())->logMessage("Workflow :: SendWelcomeEmailWhenALeadHasBeenSubmittedToCRM");

            if(empty($crm_lead_id)){
                throw new Exception("Missing CRM lead ID, while processing the lead workflow.");
            }

            $lead_details = LeadManagementController::getLeadDetailsFromCRM($crm_lead_id);

            if(!$lead_details->status){
                throw new Exception($lead_details->message ?? "Failed to fetch the lead details when sending thank you email");
            }

            if(!isset($lead_details->data->Email)){
                throw new Exception("Missing customer email address while sending the lead thank you email");
            }

            if(empty(CraydelHelperFunctions::toEmailAddress($lead_details->data->Email))){
                throw new Exception("The email address is invalid");
            }

            $student_name = isset($lead_details->data->Full_Name) && !empty($lead_details->data->Full_Name) ? CraydelHelperFunctions::toCleanString($lead_details->data->Full_Name) : null;
            $assigned_to = isset($lead_details->data->Owner) && !empty($lead_details->data->Owner) ? $lead_details->data->Owner : null;
            $student_first_name = !is_null($student_name) ? CraydelHelperFunctions::makeFirstName($student_name) : null;
            $sender_name = isset($assigned_to->name) && !empty($assigned_to->name) ? CraydelHelperFunctions::makeFirstName($assigned_to->name) : null;

            (new self())->sendEmail(
                $student_first_name,
                $lead_details->data->Email,
                sprintf(
                    LanguageTranslationHelper::translate('course_application.workflow.notifications.new_lead'),
                    $student_first_name
                ),
                view('emails.leads.workflow.new-lead',[
                    'student_first_name' => $student_first_name
                ])->render(),
                !is_null($sender_name) ? sprintf(LanguageTranslationHelper::translate('course_application.workflow.notifications.sender_name_format'), $sender_name) : 'Craydel Admissions Team',
                isset($assigned_to->email) && !empty($assigned_to->email) ? CraydelHelperFunctions::toEmailAddress($assigned_to->email) : null,
            );
        }catch (Exception $exception){
            (new self())->logException($exception);
        }
    }

    /**
     * Receive lead update or creation events
     *
     * @param Request $request
    */
    public function receive(Request $request){
        try{
            $lead_id = $request->input('lead_id');
            $email = $request->input('email');
            $phone = $request->input('phone');
            $mobile = $request->input('mobile');

            $lead_exists = false;
            $what_is_duplicate = null;

            if(!CraydelHelperFunctions::isNull(CraydelHelperFunctions::toCleanString($lead_id))){
                dispatch(new PushLeadAndOpportunitiesDataToDateLakeJob(
                    'ET875165944178',
                    json_encode(['lead_id' => $lead_id])
                ))->onQueue('push_event');
            }

            if(DB::table((new Leads())->getTable())->where('lms_provider_lead_id', CraydelHelperFunctions::toCleanString($lead_id))->exists()){
                $lead_exists = true;
                $what_is_duplicate = 'lms_provider_lead_id';
            }else{
                if(DB::table((new Leads())->getTable())->where('email', CraydelHelperFunctions::toCleanString($email))->exists()){
                    $lead_exists = true;
                    $what_is_duplicate = 'email';
                }else{
                    if(DB::table((new Leads())->getTable())->where('mobile_number', CraydelHelperFunctions::toCleanString($mobile))->exists()){
                        $lead_exists = true;
                        $what_is_duplicate = 'mobile_number';
                    }else{
                        if(DB::table((new Leads())->getTable())->where('mobile_number', CraydelHelperFunctions::toCleanString($phone))->exists()){
                            $lead_exists = true;
                            $what_is_duplicate = 'mobile_number';
                        }
                    }
                }
            }

            if(!$lead_exists){
                $this->_createNewLead($request);
            }else{
                $this->_updateLeadDetails($request, $what_is_duplicate);
            }
        }catch (Exception $exception){
            $this->logException($exception);
        }
    }

    /**
     * Create a new lead if one does not exist
     * @param Request $request
    */
    protected function _createNewLead(Request $request){
        $lead_id = $request->input('lead_id');

        DB::transaction(function () use ($request, &$saved){
            $phone = $request->input('phone');
            $mobile = $request->input('mobile');
            $created_at = Carbon::parse($request->input('created_at'))->format('Y-m-d H:i:s');
            $updated_at = Carbon::parse($request->input('updated_at'))->format('Y-m-d H:i:s');
            $last_name = $request->input('last_name');
            $first_name = $request->input('first_name');
            $updated_by = $request->input('updated_by');
            $lead_id = $request->input('lead_id');
            $email = $request->input('email');

            $saved = DB::table((new Leads())->getTable())
                ->insertOrIgnore([
                    'email' => CraydelHelperFunctions::toEmailAddress($email),
                    'first_name' => CraydelHelperFunctions::toCleanString($first_name),
                    'last_name' => CraydelHelperFunctions::toCleanString($last_name),
                    'created_at' => $created_at,
                    'updated_at' => Carbon::parse($updated_at, 'Africa/Nairobi')->toDateTimeString(),
                    'created_by' => CraydelHelperFunctions::toCleanString($updated_by),
                    'mobile_number' => call_user_func(function () use($phone, $mobile){
                        if(!empty($mobile)){
                            return CraydelHelperFunctions::toCleanString($mobile);
                        }else{
                            return !empty($phone) ? CraydelHelperFunctions::toCleanString($phone) : null;
                        }
                    }),
                    'lms_provider_lead_id' => CraydelHelperFunctions::toCleanString($lead_id),
                    'is_processed' => 1,
                    'is_picked_for_processing' => 1,
                    'time_picked_for_processing' => 0,
                    'lms_provider' => config('craydel.default_lms_provider')
                ]);
        });

        if($saved){
            event(new CourseApplicationLeadSubmittedToCRMEvent($lead_id));
        }
    }

    /**
     * Update the leads tracking information
     * @param Request $request
     * @param string|null $what_is_duplicate
     */
    protected function _updateLeadDetails(Request $request, ?string $what_is_duplicate){
        DB::transaction(function () use ($request, $what_is_duplicate){
            $updated_by = $request->input('updated_by');
            $email = $request->input('email');
            $mobile = $request->input('mobile');
            $lead_id = $request->input('lead_id');
            $updated_at = $request->input('updated_at');
            $lead = null;

            if($what_is_duplicate == 'email'){
                $lead = Leads::where('email', CraydelHelperFunctions::toCleanString($email))
                    ->first([
                        'id',
                        'first_updated_on'
                    ]);
            }elseif ($what_is_duplicate == 'mobile_number'){
                $lead = Leads::where('mobile_number', CraydelHelperFunctions::toCleanString($mobile))
                    ->first([
                        'id',
                        'first_updated_on'
                    ]);
            }elseif ($what_is_duplicate == 'lms_provider_lead_id'){
                $lead = Leads::where('lms_provider_lead_id', CraydelHelperFunctions::toCleanString($lead_id))
                    ->first([
                        'id',
                        'first_updated_on'
                    ]);
            }

            if(!is_null($lead) && empty($lead->first_updated_on)){
                $updated = DB::table((new Leads())->getTable())
                    ->where('id', $lead->id)
                    ->update([
                        'first_updated_on' => $updated_at,
                        'updated_at' => Carbon::parse($updated_at, 'Africa/Nairobi')->toDateTimeString(),
                        'updated_by' => CraydelHelperFunctions::toCleanString($updated_by)
                    ]);

                if($updated){
                    dispatch((new UpdateLeadFirstContactDate(
                        $lead_id,[
                            'Lead_First_Contacted_On' => $updated_at
                        ]
                    )))->onQueue('course_application_workflow');
                }
            }
        });
    }
}
