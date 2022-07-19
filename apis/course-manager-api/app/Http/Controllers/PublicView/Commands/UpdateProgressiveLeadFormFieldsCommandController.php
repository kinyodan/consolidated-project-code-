<?php
namespace App\Http\Controllers\PublicView\Commands;

use App\Http\Controllers\CraydelTypes\CraydelJSONResponseType;
use App\Http\Controllers\Helpers\CraydelHelperFunctions;
use App\Http\Controllers\Helpers\CraydelInternalResponseHelper;
use App\Http\Controllers\Helpers\LanguageTranslationHelper;
use App\Http\Controllers\Providers\LeadManagement\LeadManagementController;
use App\Http\Controllers\PublicView\CoursesPublicViewController;
use App\Models\Leads;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Respect\Validation\Validator;
use Respect\Validation\Validator as v;

class UpdateProgressiveLeadFormFieldsCommandController
{
    /**
     * @var CoursesPublicViewController
     */
    protected CoursesPublicViewController $coursesPublicViewController;

    /**
     * @var v $validate
     */
    protected v $validate;

    /**
     * Constructor
     * @param CoursesPublicViewController $coursesPublicViewController
     */
    public function __construct(CoursesPublicViewController $coursesPublicViewController)
    {
        $this->coursesPublicViewController = $coursesPublicViewController;
        $this->validate = new Validator();
    }

    /**
     * @param Request $request
     * @return CraydelInternalResponseHelper
     */
    protected function validate(Request $request): CraydelInternalResponseHelper
    {
        try {
            $lead_id = $request->input('lead_id');
            $course_name = $request->input('course_name');
            $student_has_passport = $request->input('student_has_passport');
            $target_year = $request->input('target_year');
            $target_intake = $request->input('target_intake');
            $target_destination = $request->input('target_destination');
            $target_budget = $request->input('target_budget');
            $how_will_you_fund_your_studies = $request->input('education_fund');

            if(!$this->validate::stringVal()->validate($target_year)){
                throw new Exception("Missing target year");
            }

            if(!$this->validate::stringVal()->validate($lead_id)){
                throw new Exception("Missing lead ID");
            }

            if(!DB::table((new Leads())->getTable())->where('id', CraydelHelperFunctions::toCleanString($lead_id))->exists()){
                throw new Exception("Invalid lead ID");
            }

            if (!$this->validate::stringVal()->validate($course_name)) {
                throw new Exception('Missing course name.');
            }

            if (!$this->validate::stringVal()->validate($student_has_passport)) {
                throw new Exception('Missing student passport flag.');
            }

            if (!$this->validate::stringVal()->validate($target_intake)) {
                throw new Exception('Miss target intake value');
            }

            if (!$this->validate::stringVal()->validate($target_destination)) {
                throw new Exception('Miss target destination value');
            }

            if (!$this->validate::stringVal()->validate($target_budget)) {
                throw new Exception('Miss target budget value');
            }

            if(!$this->validate::stringVal()->validate($how_will_you_fund_your_studies)){
                throw new Exception('Miss funding option');
            }

            return $this->coursesPublicViewController->internalResponse(new CraydelInternalResponseHelper(
                true,
                'Validated'
            ));
        } catch (Exception $exception) {
            $this->coursesPublicViewController->logException($exception);

            return $this->coursesPublicViewController->internalResponse(new CraydelInternalResponseHelper(
                false,
                $exception->getMessage()
            ));
        }
    }

    /**
     * Submit the lead details
     * @param Request $request
     * @return JsonResponse
     */
    public function submit(Request $request): JsonResponse
    {
        try{
            $validate = $this->validate($request);

            if (!$validate->status) {
                return $this->coursesPublicViewController->respondInJSON(new CraydelJSONResponseType(
                    false,
                    $validate->message
                ));
            }

            $updated = false;

            DB::transaction(function () use($request, &$updated){
                $lead_id = $request->input('lead_id');
                $target_year = $request->input('target_year');
                $course_name = CraydelHelperFunctions::toCleanString($request->input('course_name'));
                $student_has_passport = CraydelHelperFunctions::toCleanString($request->input('student_has_passport'));
                $target_intake = CraydelHelperFunctions::toCleanString($request->input('target_intake'));
                $target_destination = CraydelHelperFunctions::toCleanString($request->input('target_destination'));
                $target_budget = CraydelHelperFunctions::toCleanString($request->input('target_budget'));
                $how_will_you_fund_your_studies = CraydelHelperFunctions::toCleanString($request->input('education_fund'));

                $updated = DB::table((new Leads())->getTable())
                    ->where('id', CraydelHelperFunctions::toCleanString($lead_id))
                    ->update([
                        'course_name' => $course_name,
                        'student_has_passport' => $student_has_passport,
                        'used_progressive_lead_form' => 'Yes',
                        'current_course_intake' => $target_intake . '-' .$target_year,
                        'target_budget' => $target_budget,
                        'study_destination' => $target_destination,
                        'how_to_fund_education' => $how_will_you_fund_your_studies,
                        'updated_at' => Carbon::now()->toDateTimeString()
                    ]);
            });

            if($updated){
                $lead_id = $request->input('lead_id');
                $course_name = CraydelHelperFunctions::toCleanString($request->input('course_name'));
                $student_has_passport = CraydelHelperFunctions::toCleanString($request->input('student_has_passport'));
                $target_intake = CraydelHelperFunctions::toCleanString($request->input('target_intake'));
                $target_destination = CraydelHelperFunctions::toCleanString($request->input('target_destination'));
                $target_budget = CraydelHelperFunctions::toCleanString($request->input('target_budget'));
                $how_will_you_fund_your_studies = CraydelHelperFunctions::toCleanString($request->input('education_fund'));
                $target_year = $request->input('target_year');

                $crm_lead_id = DB::table((new Leads())->getTable())
                    ->where('id', CraydelHelperFunctions::toCleanString($lead_id))
                    ->value('lms_provider_lead_id');

                if(!empty($crm_lead_id)){
                    LeadManagementController::updateLeadDetailsOnCRM($crm_lead_id,[
                        'Course_Name' => $course_name,
                        'Student_Has_Passport' => $student_has_passport,
                        'Used_Progressive_Lead_Form' => 'Yes',
                        'Course_Current_Intake' => $target_intake,
                        'Study_Destination' => $target_destination,
                        'What_budget_per_year_do_you_have_for_your_studies' => $target_budget,
                        'How_Will_You_fund_your_studies' => $how_will_you_fund_your_studies
                    ]);
                }

                return $this->coursesPublicViewController->respondInJSON(new CraydelJSONResponseType(
                    true,
                    LanguageTranslationHelper::translate('courses.success.lead_submitted')
                ));
            }

            throw new Exception("Unable to update the lead details.");
        }catch (Exception $exception){
            $this->coursesPublicViewController->logException($exception);

            return $this->coursesPublicViewController->respondInJSON(new CraydelJSONResponseType(
                false,
                $exception->getMessage()
            ));
        }
    }
}
