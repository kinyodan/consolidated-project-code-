<?php
namespace App\Http\Controllers\Providers\LeadManagement\Zoho;

use App\Events\CourseApplicationLeadSubmittedToCRMEvent;
use App\Http\Controllers\CraydelTypes\LeadType;
use App\Http\Controllers\CraydelTypes\LeadTypeCollection;
use App\Http\Controllers\Helpers\CraydelHelperFunctions;
use App\Http\Controllers\Helpers\CraydelInternalResponseHelper;
use App\Http\Controllers\Helpers\CraydelURLHelper;
use App\Http\Controllers\Providers\LeadManagement\ILeadProvider;
use App\Http\Controllers\Traits\CanCache;
use App\Http\Controllers\Traits\CanLog;
use App\Http\Controllers\Traits\CanRespond;
use App\Models\Leads;
use Carbon\Carbon;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ZohoLeadProvider implements ILeadProvider
{
    use CanRespond, CanLog, CanCache;

    /**
     * @param LeadTypeCollection|null $leads
     * @return CraydelInternalResponseHelper
     */
    public function submit(?LeadTypeCollection $leads): CraydelInternalResponseHelper
    {
        try{
            $access_token = $this->_getToken();

            if(empty($access_token)){
                throw new Exception('The Zoho CRM API access token is missing.');
            }

            if(is_null($leads)){
                throw new Exception('Null leads collection provided while submitting the leads to the LMS provider.');
            }

            if(!$leads instanceof LeadTypeCollection){
                throw new Exception('Invalid leads collection type provided while submitting the leads to the LMS provider.');
            }

            $_leads = $this->formatLeadCollection($leads);

            $api_url = config('services.zoho_crm.leads.api_url');
            $default_lead_assignment_rule = config('services.zoho_crm.zoho_crm_default_lead_assignment_rule');

            if(empty($api_url)){
                throw new Exception('Missing Zoho CRM leads API URL.');
            }

            if(empty($default_lead_assignment_rule)){
                throw new Exception('Missing Zoho CRM lead assignment rule');
            }

            $client = new Client();

            $response = $client->request('POST', $api_url,[
                'headers' => [
                    'Authorization' => 'Bearer '.$access_token,
                    'Content-Type' => 'application/json'
                ],
                'json' => [
                    'data' => $_leads,
                    'lar_id' => $default_lead_assignment_rule
                ]
            ]);

            $result = json_decode($response->getBody()->getContents());

            if(!isset($result->data) || count($result->data) <= 0){
                throw new Exception('Error while submitting the lead details: '.print_r($result, true));
            }

            $this->_updateLeadsTable($result->data, $leads);

            return $this->internalResponse(new CraydelInternalResponseHelper(
                true,
                'Saved',
                $result->data
            ));
        }catch (Exception $exception){
            $this->logException($exception);

            return $this->internalResponse(new CraydelInternalResponseHelper(
                false,
                $exception->getMessage(),
                null,
                $exception
            ));
        } catch (GuzzleException $e) {
            $this->logException($e);

            return $this->internalResponse(new CraydelInternalResponseHelper(
                false,
                $e->getMessage(),
                null,
                $e
            ));
        }
    }

    /**
     * @param LeadTypeCollection|null $leads
     * @return CraydelInternalResponseHelper
    */
    public function forcePush(?LeadTypeCollection $leads): CraydelInternalResponseHelper
    {
        try{
            $access_token = $this->_getToken();

            if(empty($access_token)){
                throw new Exception('The Zoho CRM API access token is missing.');
            }

            if(is_null($leads)){
                throw new Exception('Null leads collection provided while force pushing the leads to the LMS provider.');
            }

            if(!$leads instanceof LeadTypeCollection){
                throw new Exception('Invalid leads collection type provided while force pushing the leads to the LMS provider.');
            }

            $_leads = $this->formatLeadCollection($leads);

            $api_url = config('services.zoho_crm.leads.force_push_leads');
            $default_lead_assignment_rule = config('services.zoho_crm.zoho_crm_default_lead_assignment_rule');

            if(empty($api_url)){
                throw new Exception('Missing Zoho CRM leads API URL.');
            }

            if(empty($default_lead_assignment_rule)){
                throw new Exception('Missing Zoho CRM lead assignment rule');
            }

            $client = new Client();

            $response = $client->request('POST', $api_url,[
                'headers' => [
                    'Authorization' => 'Bearer '.$access_token,
                    'Content-Type' => 'application/json'
                ],
                'json' => [
                    'data' => $_leads,
                    'lar_id' => $default_lead_assignment_rule
                ]
            ]);

            $result = json_decode($response->getBody()->getContents());

            if(!isset($result->data) || count($result->data) <= 0){
                throw new Exception('Error while force pushing the lead details: '.print_r($result, true));
            }

            $this->_updateLeadsTable($result->data, $leads);

            return $this->internalResponse(new CraydelInternalResponseHelper(
                true,
                'Saved',
                $result->data
            ));
        }catch (Exception $exception){
            $this->logException($exception);

            return $this->internalResponse(new CraydelInternalResponseHelper(
                false,
                $exception->getMessage(),
                null,
                $exception
            ));
        } catch (GuzzleException $e) {
            $this->logException($e);

            return $this->internalResponse(new CraydelInternalResponseHelper(
                false,
                $e->getMessage(),
                null,
                $e
            ));
        }
    }

    /***
     * Update the opportunity details
     *
     * @param string|null $lms_provider_lead_id
     * @param array|null $lead_details
     * @return CraydelInternalResponseHelper
     */
    public function updateLeadDetailsOnCRM(?string $lms_provider_lead_id, ?array $lead_details): CraydelInternalResponseHelper
    {
        try{
            $access_token = $this->_getToken();

            if(empty($access_token)){
                throw new Exception('The Zoho CRM API access token is missing.');
            }

            if(empty($lms_provider_lead_id)){
                throw new Exception('Missing CRM Lead ID');
            }

            if(!is_array($lead_details) || count($lead_details) <= 0){
                throw new Exception('Invalid or Missing CRM lead update details');
            }

            $api_url = sprintf(
                config('services.zoho_crm.leads.update_lead_details'),
                CraydelHelperFunctions::toCleanString($lms_provider_lead_id)
            );

            if(empty($api_url)){
                throw new Exception('Missing Zoho CRM lead update API URL.');
            }

            $client = new Client();
            $data = [];

            $data[] = $lead_details;

            $response = $client->request('PUT', $api_url,[
                'headers' => [
                    'Authorization' => 'Bearer '.$access_token,
                    'Content-Type' => 'application/json'
                ],
                'json' => [
                    'data' => $data
                ]
            ]);

            $result = json_decode($response->getBody()->getContents());

            if(!isset($result->data) || count($result->data) <= 0){
                throw new Exception('Error while submitting updating the lead details: '.print_r($result, true));
            }

            return $this->internalResponse(new CraydelInternalResponseHelper(
                true,
                'Lead updated',
                $result->data
            ));
        }catch (Exception $exception){
            $this->logException($exception);

            return $this->internalResponse(new CraydelInternalResponseHelper(
                false,
                $exception->getMessage(),
                null,
                $exception
            ));
        } catch (GuzzleException $e) {
            $this->logException($e);

            return $this->internalResponse(new CraydelInternalResponseHelper(
                false,
                $e->getMessage(),
                null,
                $e
            ));
        }
    }

    /***
     * Update the opportunity details
     *
     * @param string|null $crm_opportunity_id
     * @param array|null $update_details
     * @return CraydelInternalResponseHelper
     */
    public function updateOpportunityDetailsOnCRM(?string $crm_opportunity_id, ?array $update_details): CraydelInternalResponseHelper
    {
        try{
            $access_token = $this->_getToken();

            if(empty($access_token)){
                throw new Exception('The Zoho CRM API access token is missing.');
            }

            if(empty($crm_opportunity_id)){
                throw new Exception('Missing CRM opportunity ID');
            }

            if(!is_array($update_details) || count($update_details) <= 0){
                throw new Exception('Invalid or Missing CRM opportunity update details');
            }

            $api_url = sprintf(
                config('services.zoho_crm.opportunity.update_opportunity_details'),
                CraydelHelperFunctions::toCleanString($crm_opportunity_id)
            );

            if(empty($api_url)){
                throw new Exception('Missing Zoho CRM opportunity update API URL.');
            }

            $client = new Client();
            $data = [];

            $data[] = $update_details;

            $response = $client->request('PUT', $api_url,[
                'headers' => [
                    'Authorization' => 'Bearer '.$access_token,
                    'Content-Type' => 'application/json'
                ],
                'json' => [
                    'data' => $data
                ]
            ]);

            $result = json_decode($response->getBody()->getContents());

            if(!isset($result->data) || count($result->data) <= 0){
                throw new Exception('Error while submitting updating the opportunity details: '.print_r($result, true));
            }

            return $this->internalResponse(new CraydelInternalResponseHelper(
                true,
                'Opportunity updated',
                $result->data
            ));
        }catch (Exception $exception){
            $this->logException($exception);

            return $this->internalResponse(new CraydelInternalResponseHelper(
                false,
                $exception->getMessage(),
                null,
                $exception
            ));
        } catch (GuzzleException $e) {
            $this->logException($e);

            return $this->internalResponse(new CraydelInternalResponseHelper(
                false,
                $e->getMessage(),
                null,
                $e
            ));
        }
    }

    /**
     * @param LeadTypeCollection|null $leads
     * @return CraydelInternalResponseHelper
     */
    public function delete(?LeadTypeCollection $leads): CraydelInternalResponseHelper
    {
        try{
            if(is_null($leads)){
                throw new Exception('Null leads collection provided while deleting the leads to the LMS provider.');
            }

            if(!$leads instanceof LeadTypeCollection){
                throw new Exception('Invalid leads collection type provided while deleting the leads to the LMS provider.');
            }

            if($leads->count() <= 0){
                throw new Exception('No leads to delete via Zoho CRM.');
            }

            $lead_ids = [];

            foreach ($leads as $lead){
                if(is_callable([$lead, 'getLmsProviderLeadId']) && !empty($lead->getLmsProviderLeadId())){
                    $lead_ids[] = $lead->getLmsProviderLeadId();
                }
            }

            if(count($lead_ids) <= 0){
                throw new Exception('No leads to delete');
            }

            $access_token = $this->_getToken();

            if(empty($access_token)){
                throw new Exception('The Zoho CRM API access token is missing.');
            }

            if(count($lead_ids) > 1){
                $lead_ids = implode(',', $lead_ids);

                $api_url = sprintf(
                    config('services.zoho_crm.leads.delete_api_url'),
                    $lead_ids
                );
            }else{
                if(isset($lead_ids[0]) && !empty($lead_ids[0])){
                    $api_url = sprintf(
                        config('services.zoho_crm.leads.single_delete_api_url'),
                        CraydelHelperFunctions::toCleanString($lead_ids[0])
                    );
                }else{
                    throw new Exception("Empty lead ID while deleting a single lead");
                }
            }

            if(empty($api_url)){
                throw new Exception('Missing Zoho CRM leads API URL while deleting lead.');
            }

            $client = new Client();

            $response = $client->request('DELETE', $api_url,[
                'headers' => [
                    'Authorization' => 'Bearer '.$access_token,
                    'Content-Type' => 'application/json'
                ]
            ]);

            $result = json_decode($response->getBody()->getContents());

            return $this->internalResponse(new CraydelInternalResponseHelper(
                true,
                'Deleted',[
                    'result' => $result
                ]
            ));
        }catch (Exception $exception){
            $this->logException($exception);

            return $this->internalResponse(new CraydelInternalResponseHelper(
                false,
                $exception->getMessage(),
                null,
                $exception
            ));
        } catch (GuzzleException $e) {
            $this->logException($e);

            return $this->internalResponse(new CraydelInternalResponseHelper(
                false,
                $e->getMessage(),
                null,
                $e
            ));
        }
    }

    /**
     * Get Zoho CRM user details
     *
     * @param string|null $lms_user_id
     * @return CraydelInternalResponseHelper
     */
    public function getUserDetails(?string $lms_user_id): CraydelInternalResponseHelper
    {
        try{
            if(empty($lms_user_id)){
                throw new Exception("Missing or invalid LMS user ID while fetching the LMS user details");
            }

            $access_token = $this->_getToken();

            if(empty($access_token)){
                throw new Exception('The Zoho CRM API access token is missing.');
            }

            $api_url = sprintf(
                config('services.zoho_crm.users.get_user_details'),
                CraydelHelperFunctions::toCleanString($lms_user_id)
            );

            if(empty($api_url)){
                throw new Exception('Missing Zoho CRM user details API URL.');
            }

            $client = new Client();

            $response = $client->request('GET', $api_url,[
                'headers' => [
                    'Authorization' => 'Bearer '.$access_token,
                    'Content-Type' => 'application/json'
                ]
            ]);

            $result = json_decode($response->getBody()->getContents());

            return $this->internalResponse(new CraydelInternalResponseHelper(
                true,
                'Listed user details',
                $result->users[0] ?? null
            ));
        }catch (Exception $exception){
            $this->logException($exception);

            return $this->internalResponse(new CraydelInternalResponseHelper(
                false,
                $exception->getMessage(),
                null,
                $exception
            ));
        } catch (GuzzleException $e) {
            $this->logException($e);

            return $this->internalResponse(new CraydelInternalResponseHelper(
                false,
                $e->getMessage(),
                null,
                $e
            ));
        }
    }

    /**
     * Get the customer details from the CRM
     *
     * @param string|null $customer_id
     * @return CraydelInternalResponseHelper
     */
    public function getCustomerDetailsFromCRM(?string $customer_id): CraydelInternalResponseHelper
    {
        try{
            if(empty($customer_id)){
                throw new Exception("Missing or invalid CRM customer ID while fetching the CRM customer details");
            }

            $access_token = $this->_getToken();

            if(empty($access_token)){
                throw new Exception('The Zoho CRM API access token is missing.');
            }

            $api_url = sprintf(
                config('services.zoho_crm.customers.get_details'),
                CraydelHelperFunctions::toCleanString($customer_id)
            );

            if(empty($api_url)){
                throw new Exception('Missing Zoho CRM customer details API URL.');
            }

            $client = new Client();

            $response = $client->request('GET', $api_url,[
                'headers' => [
                    'Authorization' => 'Bearer '.$access_token,
                    'Content-Type' => 'application/json'
                ]
            ]);

            $result = json_decode($response->getBody()->getContents());

            return $this->internalResponse(new CraydelInternalResponseHelper(
                true,
                'Listed customer details',
                $result->data[0] ?? null
            ));
        }catch (Exception $exception){
            $this->logException($exception);

            return $this->internalResponse(new CraydelInternalResponseHelper(
                false,
                $exception->getMessage(),
                null,
                $exception
            ));
        } catch (GuzzleException $e) {
            $this->logException($e);

            return $this->internalResponse(new CraydelInternalResponseHelper(
                false,
                $e->getMessage(),
                null,
                $e
            ));
        }
    }

    /**
     * Get lead details from the CRM
     *
     * @param string|null $crm_lead_id
     * @return CraydelInternalResponseHelper
     */
    public function getLeadDetailsFromCRM(?string $crm_lead_id): CraydelInternalResponseHelper
    {
        try{
            if(empty($crm_lead_id)){
                throw new Exception("Missing CRM lead ID, while fetching the CRM lead details");
            }

            $access_token = $this->_getToken();

            if(empty($access_token)){
                throw new Exception('The Zoho CRM API access token is missing.');
            }

            $api_url = sprintf(
                config('services.zoho_crm.leads.get_lead_details'),
                CraydelHelperFunctions::toCleanString($crm_lead_id)
            );

            if(empty($api_url)){
                throw new Exception('Missing Zoho CRM lead details API URL.');
            }

            $client = new Client();

            $response = $client->request('GET', $api_url,[
                'headers' => [
                    'Authorization' => 'Bearer '.$access_token,
                    'Content-Type' => 'application/json'
                ]
            ]);

            $result = json_decode($response->getBody()->getContents());

            return $this->internalResponse(new CraydelInternalResponseHelper(
                true,
                'Listed lead details',
                $result->data[0] ?? null
            ));
        }catch (Exception $exception){
            $this->logException($exception);

            return $this->internalResponse(new CraydelInternalResponseHelper(
                false,
                $exception->getMessage(),
                null,
                $exception
            ));
        } catch (GuzzleException $e) {
            $this->logException($e);

            return $this->internalResponse(new CraydelInternalResponseHelper(
                false,
                $e->getMessage(),
                null,
                $e
            ));
        }
    }

    /**
     * Get the opportunity details from the CRM
     *
     * @param string|null $opportunity_lead_id
     * @return CraydelInternalResponseHelper
     */
    public function getOpportunityDetailsFromCRM(?string $opportunity_lead_id): CraydelInternalResponseHelper
    {
        try{
            if(empty($opportunity_lead_id)){
                throw new Exception("Missing CRM opportunity ID, while fetching the CRM opportunity details");
            }

            $access_token = $this->_getToken();

            if(empty($access_token)){
                throw new Exception('The Zoho CRM API access token is missing.');
            }

            $api_url = sprintf(
                config('services.zoho_crm.opportunity.get_details'),
                CraydelHelperFunctions::toCleanString($opportunity_lead_id)
            );

            if(empty($api_url)){
                throw new Exception('Missing Zoho CRM opportunity details API URL.');
            }

            $client = new Client();

            $response = $client->request('GET', $api_url,[
                'headers' => [
                    'Authorization' => 'Bearer '.$access_token,
                    'Content-Type' => 'application/json'
                ]
            ]);

            $result = json_decode($response->getBody()->getContents());

            return $this->internalResponse(new CraydelInternalResponseHelper(
                true,
                'Listed opportunity details',
                $result->data[0] ?? null
            ));
        }catch (Exception $exception){
            $this->logException($exception);

            return $this->internalResponse(new CraydelInternalResponseHelper(
                false,
                $exception->getMessage(),
                null,
                $exception
            ));
        } catch (GuzzleException $e) {
            $this->logException($e);

            return $this->internalResponse(new CraydelInternalResponseHelper(
                false,
                $e->getMessage(),
                null,
                $e
            ));
        }
    }

    /**
     * Update the staged leads table
     *
     * @param array|null $result
     * @param LeadTypeCollection|null $leads
    */
    protected function _updateLeadsTable(?array $result, ?LeadTypeCollection $leads){
        try{
            if(!is_array($result)){
                throw new Exception('Missing or invalid leads publishing result');
            }

            if(is_null($leads) || count($leads) <= 0){
                throw new Exception('Missing or invalid leads list while updating the staged leads table.');
            }

            $_lms_provider_lead_ids = [];

            foreach ($result as $item){
                if(isset($item->details->id) && !empty($item->details->id)){
                    $_lms_provider_lead_ids[] = $item->details->id;
                }
            }

            if(count($_lms_provider_lead_ids) <= 0){
                throw new Exception('Unable to retrieve the LMS provider keys.');
            }

            $lead_ids = [];

            foreach ($leads as $lead){
                if(is_callable([$lead, 'getId']) && !empty($lead->getId())){
                    $lead_ids[] = $lead->getId();
                }
            }

            if(count($lead_ids) !== count($_lms_provider_lead_ids)){
                throw new Exception('Count miss match between the uploaded leads and the LMS provider resulting IDs');
            }

            DB::transaction(function () use($lead_ids, $_lms_provider_lead_ids){
                for ($i = 0; $i <= count($lead_ids) - 1; $i++){
                    DB::table((new Leads())->getTable())
                        ->where('id', $lead_ids[$i])
                        ->update([
                            'is_processed' => 1,
                            'is_picked_for_processing' => 1,
                            'lms_provider' => 'ZOHO',
                            'lms_provider_lead_id' => isset($_lms_provider_lead_ids[$i]) && !empty($_lms_provider_lead_ids[$i]) ? trim($_lms_provider_lead_ids[$i]) : null,
                            'lms_provider_error_message' => call_user_func(function () use($_lms_provider_lead_ids, $i){
                                if(isset($_lms_provider_lead_ids[$i]) && !empty($_lms_provider_lead_ids[$i])){
                                    return null;
                                }else{
                                    return "Unable to update with correct LMS provider ID";
                                }
                            }),
                            'updated_at' => Carbon::now()->toDateTimeString()
                        ]);

                    event(new CourseApplicationLeadSubmittedToCRMEvent($_lms_provider_lead_ids[$i]));
                }
            });
        }catch (Exception $exception){
            $this->logException($exception);
        }
    }

    /**
     * Get access token
     *
     * @return string|null
    */
    protected function _getToken(): ?string
    {
        try{
            return $this->_refreshToken();
        }catch (Exception $exception){
            $this->logException($exception);
            return null;
        } catch (GuzzleException $e) {
            $this->logException($e);
            return null;
        }
    }

    /**
     * Refresh token
     * @throws Exception
     * @throws GuzzleException
     * @return string|null
     */
    protected function _refreshToken(): ?string
    {
        $access_token = self::cache('ZOHO_ACCESS_TOKEN');

        if(!empty($access_token)){
            return $access_token;
        }

        $zoho_refresh_token = config('services.zoho_crm.refresh_token');

        if(empty($zoho_refresh_token)){
            throw new Exception("_refreshToken in {".get_class($this)."} couldn't locate the zoho_refresh_token value");
        }

        $zoho_crm_refresh_token_url = config('services.zoho_crm.zoho_crm_refresh_token_url');
        $zoho_redirect_url = config('services.zoho_crm.zoho_redirect_url', 'https://craydel.com/');
        $client_id = config('services.zoho_crm.zoho_crm_api_client_id');
        $client_secret = config('services.zoho_crm.zoho_crm_api_client_secret');

        if(empty($zoho_crm_refresh_token_url)){
            throw new Exception('Missing Zoho CRM API refresh URL.');
        }

        if(empty($client_id)){
            throw new Exception('Missing Zoho CRM API Client ID.');
        }

        if(empty($client_secret)){
            throw new Exception('Missing Zoho CRM API Client Secret.');
        }

        $_client = new Client();

        $request = $_client->post($zoho_crm_refresh_token_url,[
            'form_params' => [
                'client_id' => $client_id,
                'client_secret' => $client_secret,
                'redirect_uri' => $zoho_redirect_url,
                'refresh_token' => $zoho_refresh_token,
                'grant_type' => 'refresh_token'
            ]
        ]);

        $response = $request->getBody()->getContents();

        if(empty($response)){
            throw new Exception("{".get_class($this)." unable to refresh the Zoho access token}");
        }

        $response = json_decode($response);

        if(empty($response)){
            throw new Exception("{".get_class($this)." unable to json decode the Zoho access token response}");
        }

        if(empty($response->access_token)){
            throw new Exception("{".get_class($this)." unable to get the access token }");
        }

        return CraydelHelperFunctions::toCleanString(
            self::cache('ZOHO_ACCESS_TOKEN', $response->access_token, 3000)
        );
    }

    /**
     * @param LeadTypeCollection $leads
     * @return Collection|null
     * @throws Exception
     */
    private function formatLeadCollection(LeadTypeCollection $leads): ?Collection
    {
        if ($leads->count() <= 0) {
            throw new Exception('No leads to publish via Zoho CRM.');
        }

        return $leads->collection()->map(function (LeadType $lead) {
            return [
                "City" => is_callable([$lead, 'getCity']) && !empty($lead->getCity()) ? trim($lead->getCity()) : "",
                "Country" => is_callable([$lead, 'getCountry']) && !empty($lead->getCountry()) ? trim($lead->getCountry()) : "",
                "Student_Country_Location" => is_callable([$lead, 'getCountry']) && !empty($lead->getCountry()) ? trim($lead->getCountry()) : "",
                "Study_Destination" => is_callable([$lead, 'getCountry']) && !empty($lead->getCountry()) ? trim($lead->getCountry()) : "",
                "Course_Name" => is_callable([$lead, 'getCourseName']) && !empty($lead->getCourseName()) ? trim($lead->getCourseName()) : "",
                "Course_Category" => is_callable([$lead, 'getCourseCategory']) && !empty($lead->getCourseCategory()) ? trim($lead->getCourseCategory()) : "",
                "Email" => is_callable([$lead, 'getEmail']) && !empty($lead->getEmail()) ? trim($lead->getEmail()) : "",
                "Parent_Full_Names" => is_callable([$lead, 'getParentFullNames']) && !empty($lead->getParentFullNames()) ? trim($lead->getParentFullNames()) : "",
                "Parent_Mobile_Number" => is_callable([$lead, 'getParentMobileNumber']) && !empty($lead->getParentMobileNumber()) ? trim($lead->getParentMobileNumber()) : "",
                "First_Name" => is_callable([$lead, 'getFirstName']) && !empty($lead->getFirstName()) ? trim($lead->getFirstName()) : "",
                "Institution_Name" => is_callable([$lead, 'getInstitutionName']) && !empty($lead->getInstitutionName()) ? trim($lead->getInstitutionName()) : "",
                "Last_Name" => is_callable([$lead, 'getLastName']) && !empty($lead->getLastName()) ? trim($lead->getLastName()) : "",
                "Lead_Source" => is_callable([$lead, 'getUtmSource']) && !empty($lead->getUtmSource()) ? trim($lead->getUtmSource()) : "",
                "Campaign_Lead_Source" => is_callable([$lead, 'getUtmSource']) && !empty($lead->getUtmSource()) ? trim($lead->getUtmSource()) : "",
                "Lead_Status" => is_callable([$lead, 'getLeadStatus']) && !empty($lead->getLeadStatus()) ? trim($lead->getLeadStatus()) : "",
                "Mobile" => is_callable([$lead, 'getMobileNumber']) && !empty($lead->getMobileNumber()) ? trim($lead->getMobileNumber()) : "",
                "Phone" => is_callable([$lead, 'getMobileNumber']) && !empty($lead->getMobileNumber()) ? trim($lead->getMobileNumber()) : "",
                "Page_URL" => is_callable([$lead, 'getPageUrl']) && !empty($lead->getPageUrl()) ? trim(CraydelURLHelper::removeParameterFromUrl($lead->getPageUrl(), 'fbclid')) : "",
                "Landing_Page_Section" => is_callable([$lead, 'getPageSection']) && !empty($lead->getPageSection()) ? trim($lead->getPageSection()) : "",
                "Referrer_URL" => is_callable([$lead, 'getReferrerUrl']) && !empty($lead->getReferrerUrl()) ? trim(CraydelURLHelper::removeParametersFromURL($lead->getReferrerUrl(), 'fbclid')) : "",
                "Description" => is_callable([$lead, 'getDescription']) && !empty($lead->getDescription()) ? trim($lead->getDescription()) : "",
                "Year_of_Birth" => is_callable([$lead, 'getYearOfBirth']) && !empty($lead->getYearOfBirth()) ? trim($lead->getYearOfBirth()) : "",
                "Lead_Medium" => is_callable([$lead, 'getUtmMedium']) && !empty($lead->getUtmMedium()) ? trim($lead->getUtmMedium()) : "",
                "Campaign_Name" => is_callable([$lead, 'getUtmCampaign']) && !empty($lead->getUtmCampaign()) ? trim($lead->getUtmCampaign()) : "",
                "Source_Campaign" => is_callable([$lead, 'getUtmCampaign']) && !empty($lead->getUtmCampaign()) ? trim($lead->getUtmCampaign()) : "Not Selected",
                "Campaign_Asset" => is_callable([$lead, 'getAssetId']) && !empty($lead->getAssetId()) ? trim($lead->getAssetId()) : "",
                "Course_Learning_Mode" => is_callable([$lead, 'getCourseLearningMode']) && !empty($lead->getCourseLearningMode()) ? trim($lead->getCourseLearningMode()) : "",
                "Course_Current_Intake" => is_callable([$lead, 'getCurrentCourseIntake']) && !empty($lead->getCurrentCourseIntake()) ? trim($lead->getCurrentCourseIntake()) : "",
                "Student_Academic_Level" => is_callable([$lead, 'getStudentAcademicLevel']) && !empty($lead->getStudentAcademicLevel()) ? trim($lead->getStudentAcademicLevel()) : "",
                "How_Will_You_Your_Education" => is_callable([$lead, 'getHowToFundEducation']) && !empty($lead->getHowToFundEducation()) ? trim($lead->getHowToFundEducation()) : "",
                "Campaign_Term" => is_callable([$lead, 'getUtmTerm']) && !empty($lead->getUtmTerm()) ? trim($lead->getUtmTerm()) : "",
                "Advert_ID" => is_callable([$lead, 'getAdId']) && !empty($lead->getAdId()) ? trim($lead->getAdId()) : "",
                "Advert_Site_Source_Name" => is_callable([$lead, 'getSiteSourceName']) && !empty($lead->getSiteSourceName()) ? trim($lead->getSiteSourceName()) : "",
                "Advert_Set_Name" => is_callable([$lead, 'getAdSetName']) && !empty($lead->getAdSetName()) ? trim($lead->getAdSetName()) : "",
                "Campaign_ID" => is_callable([$lead, 'getCampaignId']) && !empty($lead->getCampaignId()) ? trim($lead->getCampaignId()) : "",
                "Marketplace_Search_Query_ID" => is_callable([$lead, 'getMarketplaceSearchQueryId']) && !empty($lead->getMarketplaceSearchQueryId()) ? trim($lead->getMarketplaceSearchQueryId()) : "",
                "Advert_Placement" => is_callable([$lead, 'getAdPlacementPosition']) && !empty($lead->getAdPlacementPosition()) ? trim($lead->getAdPlacementPosition()) : "",
                "Advert_Name" => is_callable([$lead, 'getAdName']) && !empty($lead->getAdName()) ? trim($lead->getAdName()) : "",
                "Marketplace_Search_Term" => is_callable([$lead, 'getMarketplaceSearchTerm']) && !empty($lead->getMarketplaceSearchTerm()) ? trim($lead->getMarketplaceSearchTerm()) : "",
                "Advert_Set_ID" => is_callable([$lead, 'getAdSetId']) && !empty($lead->getAdSetId()) ? trim($lead->getAdSetId()) : "",
                "Campaign_Content" => is_callable([$lead, 'getUtmContent']) && !empty($lead->getUtmContent()) ? trim($lead->getUtmContent()) : "",
            ];
        });
    }

    /**
     * @param string|null $opportunity_lead_id
     * @return CraydelInternalResponseHelper
     */
    public function getOpportunityRelatedListsFromCRM(?string $opportunity_lead_id): CraydelInternalResponseHelper
    {
        try{
            if(empty($opportunity_lead_id)){
                throw new Exception("Missing CRM opportunity ID, while fetching the CRM opportunity listing details");
            }

            $access_token = $this->_getToken();

            if(empty($access_token)){
                throw new Exception('The Zoho CRM API access token is missing.');
            }

            $api_url = sprintf(
                config('services.zoho_crm.opportunity.get_activities'),
                CraydelHelperFunctions::toCleanString($opportunity_lead_id)
            );

            if(empty($api_url)){
                throw new Exception('Missing Zoho CRM opportunity listing API URL.');
            }

            $client = new Client();

            $response = $client->request('GET', $api_url,[
                'headers' => [
                    'Authorization' => 'Bearer '.$access_token,
                    'Content-Type' => 'application/json'
                ]
            ]);

            $result = json_decode($response->getBody()->getContents());

            return $this->internalResponse(new CraydelInternalResponseHelper(
                true,
                'Listed opportunity activities',
                $result->data ?? null
            ));
        }catch (Exception $exception){
            $this->logException($exception);

            return $this->internalResponse(new CraydelInternalResponseHelper(
                false,
                $exception->getMessage(),
                null,
                $exception
            ));
        } catch (GuzzleException $e) {
            $this->logException($e);

            return $this->internalResponse(new CraydelInternalResponseHelper(
                false,
                $e->getMessage(),
                null,
                $e
            ));
        }
    }
}
