<?php
namespace App\Http\Controllers\Providers\LeadManagement;

use App\Http\Controllers\CraydelTypes\LeadType;
use App\Http\Controllers\CraydelTypes\LeadTypeCollection;
use App\Http\Controllers\Helpers\CraydelInternalResponseHelper;
use App\Models\Leads;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use PHPUnit\Util\Exception;

class LeadManagementController
{
    /**
     * Get lead details
     *
     * @param string|null $crm_lead_id
     * @return CraydelInternalResponseHelper
     */
    public static function getLeadDetailsFromCRM(?string $crm_lead_id): CraydelInternalResponseHelper
    {
        if(empty($crm_lead_id)){
            throw new Exception("Missing CRM lead ID while fetching the lead details.");
        }

        /**
         * @var ILeadProvider $crm_provider
        */
        $crm_provider = App::make(ILeadProvider::class);

        return $crm_provider->getLeadDetailsFromCRM($crm_lead_id);
    }

    /**
     * Get opportunity details
     *
     * @param string|null $opportunity_lead_id
     * @return CraydelInternalResponseHelper
     */
    public static function getOpportunityDetailsFromCRM(?string $opportunity_lead_id): CraydelInternalResponseHelper
    {
        if(empty($opportunity_lead_id)){
            throw new Exception("Missing CRM opportunity ID while fetching the opportunity details.");
        }

        /**
         * @var ILeadProvider $crm_provider
        */
        $crm_provider = App::make(ILeadProvider::class);

        return $crm_provider->getOpportunityDetailsFromCRM($opportunity_lead_id);
    }

    /**
     * Get lead details
     *
     * @param string|null $crm_lead_id
     * @return CraydelInternalResponseHelper
     */
    public static function deleteLeadFromCRM(?string $crm_lead_id): CraydelInternalResponseHelper
    {
        if(empty($crm_lead_id)){
            throw new Exception("Missing CRM lead ID while lead from the CRM.");
        }

        /**
         * @var ILeadProvider $crm_provider
        */
        $crm_provider = App::make(ILeadProvider::class);

        $lead_id = DB::table((new Leads())->getTable())
            ->where('lms_provider_lead_id', $crm_lead_id)
            ->value('id');

        return $crm_provider->delete(new LeadTypeCollection(new LeadType($lead_id)));
    }

    /**
     * Update lead details on CRM
     *
     * @param string|null $lms_provider_lead_id
     * @param array|null $lead_details
     * @return CraydelInternalResponseHelper
     */
    public static function updateLeadDetailsOnCRM(?string $lms_provider_lead_id, ?array $lead_details): CraydelInternalResponseHelper
    {
        if(empty($lms_provider_lead_id)){
            throw new Exception("Missing CRM lead ID while updating from the lead details.");
        }

        /**
         * @var ILeadProvider $crm_provider
         */
        $crm_provider = App::make(ILeadProvider::class);

        return $crm_provider->updateLeadDetailsOnCRM($lms_provider_lead_id, $lead_details);
    }

    /**
     * Update the opportunity details on the CRM
     *
     * @param string|null $crm_opportunity_id
     * @param array|null $opportunity_details
     * @return CraydelInternalResponseHelper
     */
    public static function updateOpportunityDetailsCRM(?string $crm_opportunity_id, ?array $opportunity_details): CraydelInternalResponseHelper
    {
        if(empty($crm_opportunity_id)){
            throw new Exception("Missing CRM opportunity ID while updating from the opportunity details.");
        }

        /**
         * @var ILeadProvider $crm_provider
        */
        $crm_provider = App::make(ILeadProvider::class);

        return $crm_provider->updateOpportunityDetailsOnCRM($crm_opportunity_id, $opportunity_details);
    }

    /**
     * Get the customer details from the CRM
     *
     * @param string|null $customer_id
     * @return CraydelInternalResponseHelper
     */
    public static function getCustomerDetailsFromCRM(?string $customer_id): CraydelInternalResponseHelper
    {
        if(empty($customer_id)){
            throw new Exception("Missing CRM customer ID while fetching the customer details.");
        }

        /**
         * @var ILeadProvider $crm_provider
         */
        $crm_provider = App::make(ILeadProvider::class);

        return $crm_provider->getCustomerDetailsFromCRM($customer_id);
    }

    /**
     * Get Zoho CRM user details
     *
     * @param string|null $crm_user_id
     * @return CraydelInternalResponseHelper
     */
    public static function getUserDetailsFromCRM(?string $crm_user_id): CraydelInternalResponseHelper
    {
        if(empty($crm_user_id)){
            throw new Exception("Missing CRM user ID while fetching the user details.");
        }

        /**
         * @var ILeadProvider $crm_provider
         */
        $crm_provider = App::make(ILeadProvider::class);

        return $crm_provider->getUserDetails($crm_user_id);
    }

    /**
     * Get Zoho CRM opportunity related lists
    */
    public static function getOpportunityRelatedListsFromCRM(?string $crm_opportunity_id): CraydelInternalResponseHelper
    {
        if(empty($crm_opportunity_id)){
            throw new Exception("Missing CRM opportunity ID while fetching the opportunity related records details.");
        }

        /**
         * @var ILeadProvider $crm_provider
         */
        $crm_provider = App::make(ILeadProvider::class);

        return $crm_provider->getOpportunityRelatedListsFromCRM($crm_opportunity_id);
    }
}
