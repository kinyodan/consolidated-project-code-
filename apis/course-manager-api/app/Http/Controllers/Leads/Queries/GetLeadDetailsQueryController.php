<?php
namespace App\Http\Controllers\Leads\Queries;

use App\Http\Controllers\CraydelTypes\CraydelJSONResponseType;
use App\Http\Controllers\Helpers\CraydelHelperFunctions;
use App\Http\Controllers\Providers\LeadManagement\LeadManagementController;
use App\Http\Controllers\Traits\CanCache;
use App\Http\Controllers\Traits\CanLog;
use App\Http\Controllers\Traits\CanRespond;
use App\Models\Leads;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use function PHPUnit\Framework\isNull;

class GetLeadDetailsQueryController
{
    use CanLog, CanRespond, CanCache;

    /**
     * Get lead details by email address
     *
     * @param string|null $email_address
     * @return JsonResponse
     */
    public function detailsByEmailAddress(?string $email_address): JsonResponse
    {
        try{
            if(CraydelHelperFunctions::isNull(CraydelHelperFunctions::toEmailAddress($email_address))){
                throw new Exception('Missing email address');
            }

            return self::respondInJSON(new CraydelJSONResponseType(
                true,
                'Lead details',
                Leads::orderBy('id', 'desc')->where('email', CraydelHelperFunctions::toEmailAddress($email_address))->first()
            ));
        }catch (Exception $exception){
            self::logException($exception);

            return self::respondInJSON(new CraydelJSONResponseType(
                false,
                $exception->getMessage()
            ));
        }
    }

    /**
     * Get lead details by email address and include the CRM details
     *
     * @param string|null $email_address
     * @return JsonResponse
     */
    public function detailsByEmailAddressFromCRM(?string $email_address): JsonResponse
    {
        try{
            if(CraydelHelperFunctions::isNull(CraydelHelperFunctions::toEmailAddress($email_address))){
                throw new Exception('Missing email address');
            }

            $lead = Leads::orderBy('id', 'desc')->where('email', CraydelHelperFunctions::toEmailAddress($email_address))->first();

            if(CraydelHelperFunctions::isNull($lead->lms_provider_lead_id)){
                throw new Exception("Missing lead ID");
            }

            $lead_crm_details = LeadManagementController::getLeadDetailsFromCRM($lead->lms_provider_lead_id);

            return self::respondInJSON(new CraydelJSONResponseType(
                $lead_crm_details->status,
                $lead_crm_details->message,
                $lead_crm_details->data
            ));
        }catch (Exception $exception){
            self::logException($exception);

            return self::respondInJSON(new CraydelJSONResponseType(
                false,
                $exception->getMessage()
            ));
        }
    }

    /**
     * Get lead details from CMS by lead ID
     *
     * @param string|null $lead_id
     * @return JsonResponse
     */
    public function detailsByLeadIDFromCRM(?string $lead_id): JsonResponse
    {
        try{
            if(CraydelHelperFunctions::isNull($lead_id)){
                throw new Exception('Missing lead ID');
            }

            $lead_crm_details = LeadManagementController::getLeadDetailsFromCRM($lead_id);

            return self::respondInJSON(new CraydelJSONResponseType(
                $lead_crm_details->status,
                $lead_crm_details->message,
                $lead_crm_details->data
            ));
        }catch (Exception $exception){
            self::logException($exception);

            return self::respondInJSON(new CraydelJSONResponseType(
                false,
                $exception->getMessage()
            ));
        }
    }

    /**
     * Get opportunity details from CMS by lead ID
     *
     * @param string|null $opportunity_id
     * @return JsonResponse
     */
    public function detailsByOpportunityIDFromCRM(?string $opportunity_id): JsonResponse
    {
        try{
            if(CraydelHelperFunctions::isNull($opportunity_id)){
                throw new Exception('Missing opportunity ID');
            }

            $lead_crm_details = LeadManagementController::getOpportunityDetailsFromCRM($opportunity_id);

            return self::respondInJSON(new CraydelJSONResponseType(
                $lead_crm_details->status,
                $lead_crm_details->message,
                $lead_crm_details->data
            ));
        }catch (Exception $exception){
            self::logException($exception);

            return self::respondInJSON(new CraydelJSONResponseType(
                false,
                $exception->getMessage()
            ));
        }
    }

    /**
     * Get opportunity related lists from CRM by opportunity ID
    */
    public function getOpportunityRelatedListsFromCRMByOpportunityID(?string $opportunity_id): JsonResponse
    {
        try{
            if(CraydelHelperFunctions::isNull($opportunity_id)){
                throw new Exception('Missing opportunity ID');
            }

            $lead_crm_details = LeadManagementController::getOpportunityRelatedListsFromCRM($opportunity_id);

            return self::respondInJSON(new CraydelJSONResponseType(
                $lead_crm_details->status,
                $lead_crm_details->message,
                $lead_crm_details->data
            ));
        }catch (Exception $exception){
            self::logException($exception);

            return self::respondInJSON(new CraydelJSONResponseType(
                false,
                $exception->getMessage()
            ));
        }
    }

    /**
     * Get student details from CMS by student ID
     *
     * @param string|null $student_id
     * @return JsonResponse
     */
    public function getStudentDetailsFromCRMBasedStudentID(?string $student_id): JsonResponse
    {
        try{
            if(CraydelHelperFunctions::isNull($student_id)){
                throw new Exception('Missing student ID');
            }

            $lead_crm_details = LeadManagementController::getCustomerDetailsFromCRM($student_id);

            return self::respondInJSON(new CraydelJSONResponseType(
                $lead_crm_details->status,
                $lead_crm_details->message,
                $lead_crm_details->data
            ));
        }catch (Exception $exception){
            self::logException($exception);

            return self::respondInJSON(new CraydelJSONResponseType(
                false,
                $exception->getMessage()
            ));
        }
    }

    /**
     * Get lead details from DB by lead ID
     *
     * @param string|null $lead_identifier
     * @return JsonResponse
     */
    public function detailsByLeadIdentifier(?string $lead_identifier): JsonResponse
    {
        try{
            if(CraydelHelperFunctions::isNull($lead_identifier)){
                throw new Exception('Missing lead identifier');
            }

            $lms_provider_lead_id = self::cache('lead_id_for_the_lead_identifier_'.CraydelHelperFunctions::slugifyString($lead_identifier));

            if(CraydelHelperFunctions::isNull($lms_provider_lead_id)){
                if(CraydelHelperFunctions::isEmail($lead_identifier)){
                    $lms_provider_lead_id = DB::table((new Leads())->getTable())
                        ->where('email', CraydelHelperFunctions::toEmailAddress($lead_identifier))
                        ->value('lms_provider_lead_id');
                    $queries = DB::getQueryLog();
                }else{
                    $lms_provider_lead_id = DB::table((new Leads())->getTable())
                        ->where('lms_provider_lead_id', CraydelHelperFunctions::toCleanString($lead_identifier))
                        ->value('lms_provider_lead_id');
                }

                self::cache(
                    'lead_id_for_the_lead_identifier_'.CraydelHelperFunctions::slugifyString($lead_identifier),
                    $lms_provider_lead_id
                );
            }

            return self::respondInJSON(new CraydelJSONResponseType(
                true,
                'Fetching lead ID', [
                    'lms_provider_lead_id' => !CraydelHelperFunctions::isNull($lms_provider_lead_id) ? $lms_provider_lead_id : null
                ]
            ));
        }catch (Exception $exception){
            self::logException($exception);

            return self::respondInJSON(new CraydelJSONResponseType(
                false,
                $exception->getMessage()
            ));
        }
    }
}
