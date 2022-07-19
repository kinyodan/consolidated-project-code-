<?php
namespace App\Http\Controllers\Courses\Commands;

use App\Http\Controllers\Helpers\CraydelHelperFunctions;
use App\Http\Controllers\Traits\CanLog;
use App\Models\Course;
use App\Models\Leads;
use Exception;
use Illuminate\Support\Facades\DB;

class UpdateCourseStaticsCommandController
{
    use CanLog;

    /**
     * Update the total lead count after the lead is submitted to the CRM
     *
     * @param string|null $crm_lead_id
     * @throws Exception
     */
    public function updateLeadCountAfterLeadIsSubmittedToCRM(?string $crm_lead_id){
        $crm_lead_id = CraydelHelperFunctions::toCleanString($crm_lead_id);

        if(empty($crm_lead_id)){
            throw new Exception("Missing CRM lead ID while updating the course lead count.");
        }

        $lead_details = DB::table((new Leads())->getTable())
            ->where('lms_provider_lead_id', trim($crm_lead_id))
            ->select(['course_code'])
            ->first();

        if(!isset($lead_details->course_code) || is_null($lead_details->course_code)){
            throw new Exception("Missing course code while updating the course lead count");
        }

        $course_code = $lead_details->course_code;

        DB::transaction(function () use($course_code){
            DB::table((new Course())->getTable())
                ->where('course_code', CraydelHelperFunctions::toCleanString($course_code))
                ->update([
                    'total_leads_submitted_to_crm' => DB::raw('total_leads_submitted_to_crm + 1'),
                ]);
        });
    }
}
