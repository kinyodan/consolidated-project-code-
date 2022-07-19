<?php
namespace App\Http\Controllers\Application\Commands\Workflow\Opportunity\Commands;

use App\Http\Controllers\Helpers\CraydelHelperFunctions;
use App\Http\Controllers\Helpers\LanguageTranslationHelper;
use App\Http\Controllers\Helpers\ParsePhoneNumberHelper;
use App\Http\Controllers\Providers\LeadManagement\LeadManagementController;
use App\Http\Controllers\Traits\CanSendEmail;
use Exception;
use Throwable;

class OnOpportunityApplicationDoneCommandController
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
        $which_Intake = $opportunity->Which_Intake ?? null;
        $level_of_study_are_you_applying_for = $opportunity->What_level_of_study_are_you_applying_for ?? null;
        $Passport_ID_Bio_Page = $opportunity->Passport_ID_Bio_Page ?? null;
        $Degree_Certificates_transcripts = $opportunity->Degree_Certificates_ranscripts ?? null;
        $High_School_Certificates1 = $opportunity->High_School_Certificates1 ?? null;
        $Reference_Letter = $opportunity->Reference_Letter ?? null;
        $CV = $opportunity->CV ?? null;
        $Personal_Statement = $opportunity->Personal_Statement ?? null;
        $Visa_is_required = $opportunity->Visa_is_required ?? null;
        $Deposit_Amount_Paid = $opportunity->Deposit_Amount_Paid ?? null;
        $missing_requirements = [];

        $is_missing_course_requirements = call_user_func(function () use($opportunity, $Deposit_Amount_Paid){
            $course_name = $opportunity->Course_Name ?? null;
            $institution_name = $opportunity->Institution_Name ?? null;
            $study_destination = $opportunity->Study_Destination ?? null;
            $level_of_study_are_you_applying_for = $opportunity->What_level_of_study_are_you_applying_for ?? null;
            $Visa_is_required = $opportunity->Visa_is_required ?? null;

            if(empty($course_name) || $course_name == 'Undecided'){
                return true;
            }

            if(empty($institution_name) || $institution_name == 'Undecided'){
                return true;
            }

            if(empty($study_destination) || $study_destination == 'Undecided'){
                return true;
            }

            if(empty($level_of_study_are_you_applying_for) || $level_of_study_are_you_applying_for == 'Undecided'){
                return true;
            }

            if(CraydelHelperFunctions::isNull($Visa_is_required)){
                return true;
            }

            if(CraydelHelperFunctions::isNull($Deposit_Amount_Paid)){
                return true;
            }

            return false;
        });

        if($is_missing_course_requirements){
            OnOpportunityCourseDecidedCommandController::process($opportunity);
        }else{
            if(empty($which_Intake) || $which_Intake == 'Undecided'){
                array_push($missing_requirements, 'Which Intake?');
            }

            if($level_of_study_are_you_applying_for == "Masters" || $level_of_study_are_you_applying_for == "PHD"){
                array_push($missing_requirements, [
                    empty($Passport_ID_Bio_Page) ? 'Passport/ID/Birth Certificate (Bio Page)' : null,
                    empty($High_School_Certificates1) ? 'High school Certificate' : null,
                    empty($Degree_Certificates_transcripts) ? 'Bachelors Degree Certificates and Transcripts' : null,
                    empty($Reference_Letter) ? 'Academic Reference and Professional Reference Letter' : null,
                    empty($CV) ? 'CV' : null,
                    empty($Personal_Statement) ? 'Personal Statement' : null,
                    CraydelHelperFunctions::isNull($Visa_is_required) ? 'Visa is required' : null,
                    CraydelHelperFunctions::isNull($Deposit_Amount_Paid) ? 'Deposit Amount Paid' : null
                ]);
            }else{
                array_push($missing_requirements, [
                    empty($Passport_ID_Bio_Page) ? 'Passport/ID/Birth Certificate (Bio Page)' : null,
                    empty($High_School_Certificates1) ? 'High school Certificate' : null,
                    CraydelHelperFunctions::isNull($Visa_is_required) ? 'Visa is required' : null,
                    CraydelHelperFunctions::isNull($Deposit_Amount_Paid) ? 'Deposit Amount Paid' : null
                ]);
            }

            $missing_requirements = collect($missing_requirements)->flatten()->reject(function ($requirement){
                return is_null($requirement);
            });

            if(count($missing_requirements) > 0){
                OnOpportunityDocumentCompletedCommandController::process($opportunity);
            }else{
                self::notifyStudent($opportunity);
            }
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
                            LanguageTranslationHelper::translate('course_application.workflow.notifications.course_application_submitted_subject'),
                            !empty($contact_name) ? " ".$contact_name : ""
                        ),
                        view('emails.leads.workflow.course-application-completed',[
                            'student_first_name' => CraydelHelperFunctions::makeFirstName($contact_name),
                            'course_name' => $course_name,
                            'institution_name' => $institution_name,
                            'country_name' => $study_destination,
                            'counsellor_name' => $counsellor_name,
                            'counsellor_phone_number' => '<a href="tel:'.$counsellor_phone_number.'">'.$counsellor_phone_number.'</a>',
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
}
