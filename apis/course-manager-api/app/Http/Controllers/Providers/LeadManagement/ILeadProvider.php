<?php
namespace App\Http\Controllers\Providers\LeadManagement;

use App\Http\Controllers\CraydelTypes\LeadTypeCollection;
use App\Http\Controllers\Helpers\CraydelInternalResponseHelper;

interface ILeadProvider
{
    const FORMAT_DATA_FOR_INSERT = 'INSERT';

    /**
     * Submit a new lead.
     * @param LeadTypeCollection|null $leads
     * @return CraydelInternalResponseHelper
     */
    public function submit(?LeadTypeCollection $leads): CraydelInternalResponseHelper;

    /**
     * Update the lead details on the CRM
     *
     * @param string|null $lms_provider_lead_id
     * @param array|null $lead_details
     */
    public function updateLeadDetailsOnCRM(?string $lms_provider_lead_id, ?array $lead_details): CraydelInternalResponseHelper;

    /**
     * Update the opportunity details on the CRM
     *
     * @param string|null $crm_opportunity_id
     * @param array|null $update_details
     */
    public function updateOpportunityDetailsOnCRM(?string $crm_opportunity_id, ?array $update_details): CraydelInternalResponseHelper;

    /**
     * Delete lead
     * @param LeadTypeCollection|null $leads
     *
     * @return CraydelInternalResponseHelper
    */
    public function delete(?LeadTypeCollection $leads): CraydelInternalResponseHelper;

    /**
     * Get LMS user details
     *
     * @param string|null $lms_user_id
     * @return CraydelInternalResponseHelper
    */
    public function getUserDetails(?string $lms_user_id): CraydelInternalResponseHelper;

    /**
     * Get lead details from the CRM
     *
     * @param string|null $crm_lead_id
     * @return CraydelInternalResponseHelper
     */
    public function getLeadDetailsFromCRM(?string $crm_lead_id): CraydelInternalResponseHelper;

    /**
     * Get the opportunity details from the CRM
     *
     * @param string|null $opportunity_lead_id
     * @return CraydelInternalResponseHelper
     */
    public function getOpportunityDetailsFromCRM(?string $opportunity_lead_id): CraydelInternalResponseHelper;

    /**
     * Get the customer details from CRM
     *
     * @param string|null $customer_id
     * @return CraydelInternalResponseHelper
    */
    public function getCustomerDetailsFromCRM(?string $customer_id): CraydelInternalResponseHelper;

    /**
     * Get the opportunity details from the CRM
     *
     * @param string|null $opportunity_lead_id
     * @return CraydelInternalResponseHelper
     */
    public function getOpportunityRelatedListsFromCRM(?string $opportunity_lead_id): CraydelInternalResponseHelper;
}
