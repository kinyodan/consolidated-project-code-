<?php
namespace App\Http\Controllers\Application\Commands\Workflow\Opportunity\Commands;

use App\Http\Controllers\Helpers\CraydelHelperFunctions;
use App\Http\Controllers\Helpers\LanguageTranslationHelper;
use App\Http\Controllers\Helpers\ParsePhoneNumberHelper;
use App\Http\Controllers\Providers\LeadManagement\LeadManagementController;
use App\Http\Controllers\Traits\CanSendEmail;
use Exception;
use Throwable;

class OnOpportunityDocumentCompletedCommandController
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
        $Passport_ID_Bio_Page = $opportunity->Passport_ID_Bio_Page ?? null;
        $Degree_Certificates_transcripts = $opportunity->Degree_Certificates_ranscripts ?? null;
        $High_School_Certificates1 = $opportunity->High_School_Certificates1 ?? null;
        $Reference_Letter = $opportunity->Reference_Letter ?? null;
        $CV = $opportunity->CV ?? null;
        $Personal_Statement = $opportunity->Personal_Statement ?? null;

        $missing_requirements = [];
        $is_missing_course_requirements = call_user_func(function () use($course_name, $institution_name, $study_destination, $level_of_study_are_you_applying_for){
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

            return false;
        });

        if($is_missing_course_requirements){
            OnOpportunityCourseDecidedCommandController::process($opportunity);
        }else{
            $which_Intake = $opportunity->Which_Intake ?? null;
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
                    empty($Personal_Statement) ? 'Personal Statement' : null
                ]);
            }else{
                array_push($missing_requirements, [
                    empty($Passport_ID_Bio_Page) ? 'Passport/ID/Birth Certificate (Bio Page)' : null,
                    empty($High_School_Certificates1) ? 'High school Certificate' : null
                ]);
            }

            $missing_requirements = collect($missing_requirements)->flatten()->reject(function ($requirement){
                return is_null($requirement);
            });

            if(count($missing_requirements) > 0){
                self::notifyOpportunityOwner($opportunity, $missing_requirements->toArray());

                /*LeadManagementController::updateOpportunityDetailsCRM(
                    $opportunity->id,[
                    'Stage' => 'Awaiting Documents'
                ]);*/
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
