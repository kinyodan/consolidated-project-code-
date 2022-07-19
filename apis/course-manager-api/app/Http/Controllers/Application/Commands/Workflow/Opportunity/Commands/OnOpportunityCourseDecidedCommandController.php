<?php
namespace App\Http\Controllers\Application\Commands\Workflow\Opportunity\Commands;

use App\Http\Controllers\Helpers\CraydelHelperFunctions;
use App\Http\Controllers\Helpers\LanguageTranslationHelper;
use App\Http\Controllers\Helpers\ParsePhoneNumberHelper;
use App\Http\Controllers\Providers\LeadManagement\LeadManagementController;
use App\Http\Controllers\Traits\CanSendEmail;
use Exception;
use Throwable;

class OnOpportunityCourseDecidedCommandController
{
    use CanSendEmail;

    /**
     * Process the opportunity after the user moves the opportunity to course decided
     *
     * @param object|null $opportunity
     * @throws Exception
     * @throws Throwable
     */
    public static function process(?object $opportunity){
        if(!is_object($opportunity)){
            throw new Exception("Missing or invalid opportunity object");
        }

        self::review($opportunity);
    }

    /**
     * Check if all the requirements are available
     *
     * @param object|null $opportunity
     * @throws Throwable
     */
    protected static function review(?object $opportunity){
        $course_name = $opportunity->Course_Name ?? null;
        $institution_name = $opportunity->Institution_Name ?? null;
        $study_destination = $opportunity->Study_Destination ?? null;
        $level_of_study_are_you_applying_for = $opportunity->What_level_of_study_are_you_applying_for ?? null;

        $missing_requirements = [];

        if(empty($course_name) || $course_name == 'Undecided'){
            $missing_requirements[] = 'Course name (s)';
        }

        if(empty($institution_name) || $institution_name == 'Undecided'){
            $missing_requirements[] = 'Institution name (s)';
        }

        if(empty($study_destination) || $study_destination == 'Undecided'){
            $missing_requirements[] = 'Study Destination';
        }

        if(empty($level_of_study_are_you_applying_for) || $level_of_study_are_you_applying_for == 'Undecided'){
            $missing_requirements[] = 'What level of study are you applying for?';
        }

        if(count($missing_requirements) > 0){
            self::notifyOpportunityOwner($opportunity, $missing_requirements);

            /*LeadManagementController::updateOpportunityDetailsCRM(
                $opportunity->id,[
                    'Stage' => 'Open Opportunity'
            ]);*/
        }else{
            self::notifyStudent($opportunity);
        }
    }

    /**
     * Notify student
     *
     * @param object|null $opportunity
     * @throws Throwable
     */
    protected static function notifyStudent(?object $opportunity){
        if(isset($opportunity->Owner->email) && !empty($opportunity->Owner->email)){
            $opportunity_contact_id = isset($opportunity->Contact_Name->id) && !empty($opportunity->Contact_Name->id) ? $opportunity->Contact_Name->id : null;
            $course_name = $opportunity->Course_Name ?? null;
            $institution_name = $opportunity->Institution_Name ?? null;
            $study_destination = $opportunity->Study_Destination ?? null;
            $level_of_study_are_you_applying_for = $opportunity->What_level_of_study_are_you_applying_for ?? null;
            $assigned_user_phone_number = LeadManagementController::getUserDetailsFromCRM($opportunity->Owner->id);

            if(!empty($opportunity_contact_id) && !empty($course_name) && !empty($institution_name)){
                $assigned_user_phone_number = isset($assigned_user_phone_number->data->mobile) && !empty($assigned_user_phone_number->data->mobile) ? $assigned_user_phone_number->data->mobile : "";
                $contact = LeadManagementController::getCustomerDetailsFromCRM($opportunity_contact_id);
                $contact_email = isset($contact->data->Email) ? CraydelHelperFunctions::toEmailAddress($contact->data->Email) : null;
                $contact_name = isset($contact->data->First_Name) && !empty($contact->data->First_Name) ? CraydelHelperFunctions::makeFirstName($contact->data->First_Name) : "";

                if(!empty($contact_email)){
                    $counsellor_phone_number = !empty($assigned_user_phone_number) ? $assigned_user_phone_number : config('services.admissions_team_default_number', '0783125125');
                    $counsellor_phone_number = ParsePhoneNumberHelper::makeNationalizedMobileNumber('KE', $counsellor_phone_number);
                    $counsellor_name = isset($opportunity->Owner->name) && !empty($opportunity->Owner->name) ? " ".CraydelHelperFunctions::makeFirstName($opportunity->Owner->name) : "";

                    (new self())->sendEmail(
                        !empty($contact_name) ? $contact_name : "Student",
                        $contact_email,
                        sprintf(
                            LanguageTranslationHelper::translate('course_application.workflow.notifications.course_selected_email_subject'),
                            !empty($contact_name) ? " ".$contact_name : ""
                        ),
                        view('emails.leads.workflow.course-selected-lead',[
                            'student_first_name' => CraydelHelperFunctions::makeFirstName($contact_name),
                            'course_name' => $course_name,
                            'institution_name' => $institution_name,
                            'country_name' => $study_destination,
                            'counsellor_name' => $counsellor_name,
                            'counsellor_phone_number' => '<a href="tel:'.$counsellor_phone_number.'">'.$counsellor_phone_number.'</a>',
                            'admission_requires' => call_user_func(function () use($level_of_study_are_you_applying_for){
                                if($level_of_study_are_you_applying_for == "Masters"){
                                    return [
                                        'Passport/ID',
                                        'High school Certificate',
                                        'Bachelors Degree Certificates and Transcripts',
                                        'Academic Reference and Professional Reference Letter',
                                        'CV',
                                        'Personal Statement '
                                    ];
                                }elseif($level_of_study_are_you_applying_for == 'PHD'){
                                    return [
                                        'Passport/ID',
                                        'High school Certificate',
                                        'Bachelors Degree Certificates and Transcripts',
                                        'Academic Reference and Professional Reference Letter',
                                        'CV',
                                        'Personal Statement '
                                    ];
                                }else{
                                    return [
                                        'Passport/ID',
                                        'High school Certificate',
                                        'ALL Post-Secondary Certificates'
                                    ];
                                }
                            })
                        ])->render(),
                        sprintf(
                            LanguageTranslationHelper::translate('course_application.workflow.notifications.sender_name_format'),
                            $counsellor_name
                        ),
                        $opportunity->Owner->email
                    );
                }
            }
        }
    }

    /**
     * Notify opportunity owner
     *
     * @param object|null $opportunity
     * @param array|null $missing_requirements
     * @throws Throwable
     */
    protected static function notifyOpportunityOwner(?object $opportunity, ?array $missing_requirements){
        if(isset($opportunity->Owner->email) && !empty($opportunity->Owner->email)){
            $opportunity_owner_email = CraydelHelperFunctions::toEmailAddress($opportunity->Owner->email);

            if(!empty($opportunity_owner_email)){
                $opportunity_owner_name = isset($opportunity->Owner->name) && !empty($opportunity->Owner->name) ? $opportunity->Owner->name : 'User';

                if(env('WORKFLOW_IS_LOCAL') === true){
                    $opportunity_owner_email = 'john.nguru@craydel.com';
                    $opportunity_owner_name = 'John Nguru';
                }

                (new self())->sendEmail(
                    $opportunity_owner_name,
                    $opportunity_owner_email,
                    'Missing Opportunity Details',
                    view('emails.leads.workflow.internal.opportunity-missing-data',[
                        'user_first_name' => CraydelHelperFunctions::makeFirstName($opportunity_owner_name),
                        'current_opportunity_stage' => isset($opportunity->Stage) && !empty($opportunity->Stage) ? $opportunity->Stage : "Undefined",
                        'opportunity_id' => isset($opportunity->id) && !empty($opportunity->id) ? $opportunity->id : "",
                        'missing_information' => $missing_requirements,
                        'opportunity_name' => isset($opportunity->Deal_Name) && !empty($opportunity->Deal_Name) ? $opportunity->Deal_Name : "Not defined",
                    ])->render()
                );
            }
        }
    }
}
