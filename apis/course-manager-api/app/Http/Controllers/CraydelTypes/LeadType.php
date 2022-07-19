<?php
namespace App\Http\Controllers\CraydelTypes;

use App\Http\Controllers\Helpers\CraydelHelperFunctions;
use App\Http\Controllers\Traits\CanLog;
use App\Models\Leads;
use Illuminate\Support\Facades\DB;

class LeadType
{
    use CanLog;

    /**
     * @var int|null $id
    */
    private ?int $id;

    /**
     * @var string|null $city
    */
    private ?string $city;

    /**
     * @var string|null $company
    */
    private ?string $company;

    /**
     * @var string|null $country
    */
    private ?string $country;

    /**
     * @var string|null $student_country_location
    */
    private ?string $student_country_location;

    /**
     * @var string|null $study_destination
    */
    private ?string $study_destination;

    /**
     * @var string|null $course_Name
    */
    private ?string $course_name;

    /**
     * @var string|null $course_category
     */
    private ?string $course_category;

    /**
     * @var string|null $course_learning_mode
    */
    private ?string $course_learning_mode;

    /**
     * @var string|null $current_course_intake
    */
    private ?string $current_course_intake;

    /**
     * @var string|null $student_academic_level
    */
    private ?string $student_academic_level;

    /**
     * @var string|null $how_to_fund_education
    */
    private ?string $how_to_fund_education;

    /**
     * @var string|null $description
    */
    private ?string $description;

    /**
     * @var string|null $email
    */
    private ?string $email;

    /**
     * @var string|null $first_name
    */
    private ?string $first_name;

    /**
     * @var string|null $last_name
     */
    private ?string $last_name;

    /**
     * @var string|null $mobile_number
     */
    private ?string $mobile_number;

    /**
     * @var string|null $parent_full_names
    */
    private ?string $parent_full_names;

    /**
     * @var string|null $parent_mobile_number
    */
    private ?string $parent_mobile_number;

    /**
     * @var string|null $institution_name
    */
    private ?string $institution_name;

    /**
     * @var string|null $partner_agent
    */
    private ?string $partner_agent;

    /**
     * @var string|null $page_url
    */
    private ?string $page_url;

    /**
     * @var string|null $page_section
    */
    private ?string $page_section;

    /**
     * @var string|null $referrer_url
    */
    private ?string $referrer_url;

    /**
     * @var string|null $default_lead_source
    */
    private ?string $default_lead_source;

    /**
     * @var string|null $lead_status
    */
    private ?string $lead_status;

    /**
     * @var string|null $lms_provider_lead_id
    */
    private ?string $lms_provider_lead_id;

    /**
     * @var string|null $year_of_birth
    */
    private ?string $year_of_birth;

    /**
     * @var string|null $utm_source
    */
    private ?string $utm_source;

    /**
     * @var string|null $utm_medium
    */
    private ?string $utm_medium;

    /**
     * @var string|null $utm_campaign
    */
    private ?string $utm_campaign;

    /**
     * @var string|null $utm_term
    */
    private ?string $utm_term;

    /**
     * @var string|null $asset_id
    */
    private ?string $asset_id;

    /**
     * @var string|null $ad_id
    */
    private ?string $ad_id;

    /**
     * @var string|null $ad_set_id
    */
    private ?string $ad_set_id;

    /**
     * @var string|null $campaign_id
    */
    private ?string $campaign_id;

    /**
     * @var string|null $ad_name
    */
    private ?string $ad_name;

    /**
     * @var string|null $ad_set_name
    */
    private ?string $ad_set_name;

    /**
     * @var string|null $ad_placement_position
    */
    private ?string $ad_placement_position;

    /**
     * @var string|null $site_source_name
    */
    private ?string $site_source_name;

    /**
     * @var string|null $utm_content
    */
    private ?string $utm_content;

    /**
     * @var string|null $marketplace_search_term
    */
    private ?string $marketplace_search_term;

    /**
     * @var string|null $marketplace_search_query_id
    */
    private ?string $marketplace_search_query_id;

    /**
     * @return string|null
     */
    public function getCity(): ?string
    {
        return $this->city;
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     * @return LeadType
     */
    public function setId(?int $id): LeadType
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @param string|null $city
     *
     * @return $this
     */
    public function setCity(?string $city): LeadType
    {
        $this->city = $city;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getCompany(): ?string
    {
        return $this->company;
    }

    /**
     * @param string|null $company
     *
     * @return $this
     */
    public function setCompany(?string $company): LeadType
    {
        if (CraydelHelperFunctions::isNull($company)) {
            $this->company = null;
        }else{
            $this->company = $company;
        }

        return $this;
    }

    /**
     * @return string|null
     */
    public function getCountry(): ?string
    {
        return $this->country;
    }

    /**
     * @param string|null $country
     *
     * @return $this
     */
    public function setCountry(?string $country): LeadType
    {
        $this->country = $country;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getStudentCountryLocation(): ?string
    {
        return $this->student_country_location;
    }

    /**
     * @param string|null $student_country_location
     */
    public function setStudentCountryLocation(?string $student_country_location): void
    {
        $this->student_country_location = $student_country_location;
    }

    /**
     * @return string|null
     */
    public function getStudyDestination(): ?string
    {
        return $this->study_destination;
    }

    /**
     * @param string|null $study_destination
     */
    public function setStudyDestination(?string $study_destination): void
    {
        $this->study_destination = $study_destination;
    }

    /**
     * @return string|null
     */
    public function getCourseName(): ?string
    {
        return $this->course_name;
    }

    /**
     * @param string|null $course_name
     *
     * @return $this;
     */
    public function setCourseName(?string $course_name): LeadType
    {
        $this->course_name = $course_name;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getCourseCategory(): ?string
    {
        return $this->course_category;
    }

    /**
     * @param string|null $course_category
     * @return void
     */
    public function setCourseCategory(?string $course_category): LeadType
    {
        $this->course_category = $course_category;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getCourseLearningMode(): ?string
    {
        return $this->course_learning_mode;
    }

    /**
     * @param string|null $course_learning_mode
     * @return LeadType
     */
    public function setCourseLearningMode(?string $course_learning_mode): LeadType
    {
        $this->course_learning_mode = $course_learning_mode;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getCurrentCourseIntake(): ?string
    {
        return $this->current_course_intake;
    }

    /**
     * @param string|null $current_course_intake
     * @return LeadType
     */
    public function setCurrentCourseIntake(?string $current_course_intake): LeadType
    {
        $this->current_course_intake = $current_course_intake;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getStudentAcademicLevel(): ?string
    {
        return $this->student_academic_level;
    }

    /**
     * @param string|null $student_academic_level
     * @return LeadType
     */
    public function setStudentAcademicLevel(?string $student_academic_level): LeadType
    {
        $this->student_academic_level = $student_academic_level;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getHowToFundEducation(): ?string
    {
        return $this->how_to_fund_education;
    }

    /**
     * @param string|null $how_to_fund_education
     */
    public function setHowToFundEducation(?string $how_to_fund_education): void
    {
        $this->how_to_fund_education = $how_to_fund_education;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string|null $description
     *
     * @return $this
     */
    public function setDescription(?string $description): LeadType
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string|null $email
     *
     * @return $this
     */
    public function setEmail(?string $email): LeadType
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getFirstName(): ?string
    {
        return $this->first_name;
    }

    /**
     * @param string|null $first_name
     *
     * @return $this
     */
    public function setFirstName(?string $first_name): LeadType
    {
        $this->first_name = $first_name;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getLastName(): ?string
    {
        return $this->last_name;
    }

    /**
     * @param string|null $last_name
     *
     * @return $this
     */
    public function setLastName(?string $last_name): LeadType
    {
        $this->last_name = $last_name;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getParentFullNames(): ?string
    {
        return $this->parent_full_names;
    }

    /**
     * @param string|null $parent_full_names
     */
    public function setParentFullNames(?string $parent_full_names): void
    {
        $this->parent_full_names = $parent_full_names;
    }

    /**
     * @return string|null
     */
    public function getParentMobileNumber(): ?string
    {
        return $this->parent_mobile_number;
    }

    /**
     * @param string|null $parent_mobile_number
     */
    public function setParentMobileNumber(?string $parent_mobile_number): void
    {
        $this->parent_mobile_number = $parent_mobile_number;
    }

    /**
     * @return string|null
     */
    public function getMobileNumber(): ?string
    {
        return $this->mobile_number;
    }

    /**
     * @param string|null $mobile_number
     *
     * @return $this
     */
    public function setMobileNumber(?string $mobile_number): LeadType
    {
        $this->mobile_number = $mobile_number;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getInstitutionName(): ?string
    {
        return $this->institution_name;
    }

    /**
     * @param string|null $institution_name
     *
     * @return $this
     */
    public function setInstitutionName(?string $institution_name): LeadType
    {
        $this->institution_name = $institution_name;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getPartnerAgent(): ?string
    {
        return $this->partner_agent;
    }

    /**
     * @param string|null $partner_agent
     */
    public function setPartnerAgent(?string $partner_agent): void
    {
        $this->partner_agent = $partner_agent;
    }

    /**
     * @return string|null
     */
    public function getPageUrl(): ?string
    {
        return $this->page_url;
    }

    /**
     * @param string|null $page_url
     *
     * @return $this
     */
    public function setPageUrl(?string $page_url): LeadType
    {
        if (CraydelHelperFunctions::isNull($page_url)) {
            $this->page_url = null;
        }else{
            $this->page_url = $page_url;
        }

        return $this;
    }

    /**
     * @return string|null
     */
    public function getPageSection(): ?string
    {
        return $this->page_section;
    }

    /**
     * @param string|null $page_section
     *
     * @return $this
     */
    public function setPageSection(?string $page_section): LeadType
    {
        if (CraydelHelperFunctions::isNull($page_section)) {
            $this->page_section = null;
        }else{
            $this->page_section = $page_section;
        }

        return $this;
    }

    /**
     * @return string|null
     */
    public function getReferrerUrl(): ?string
    {
        return $this->referrer_url;
    }

    /**
     * @param string|null $referrer_url
     *
     * @return $this
     */
    public function setReferrerUrl(?string $referrer_url): LeadType
    {
        if (CraydelHelperFunctions::isNull($referrer_url)) {
            $this->referrer_url = null;
        }else{
            $this->referrer_url = $referrer_url;
        }

        return $this;
    }

    /**
     * @return string|null
     */
    public function getDefaultLeadSource(): ?string
    {
        return $this->default_lead_source;
    }

    /**
     * @param string|null $default_lead_source
     *
     * @return $this
     */
    public function setDefaultLeadSource(?string $default_lead_source): LeadType
    {
        $this->default_lead_source = $default_lead_source;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getLeadStatus(): ?string
    {
        return $this->lead_status;
    }

    /**
     * @param string|null $lead_status
     *
     * @return $this
     */
    public function setLeadStatus(?string $lead_status): LeadType
    {
        $this->lead_status = $lead_status;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getLmsProviderLeadId(): ?string
    {
        return $this->lms_provider_lead_id;
    }

    /**
     * @param string|null $lms_provider_lead_id
     * @return LeadType
     */
    public function setLmsProviderLeadId(?string $lms_provider_lead_id): LeadType
    {
        $this->lms_provider_lead_id = $lms_provider_lead_id;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getUtmSource(): ?string
    {
        return !empty($this->utm_source) ? $this->utm_source : $this->getDefaultLeadSource();
    }

    /**
     * @param string|null $utm_source
     */
    public function setUtmSource(?string $utm_source): void
    {
        $this->utm_source = $utm_source;
    }

    /**
     * @return string|null
     */
    public function getUtmMedium(): ?string
    {
        return $this->utm_medium;
    }

    /**
     * @param string|null $utm_medium
     */
    public function setUtmMedium(?string $utm_medium): void
    {
        $this->utm_medium = $utm_medium;
    }

    /**
     * @return string|null
     */
    public function getUtmCampaign(): ?string
    {
        return $this->utm_campaign;
    }

    /**
     * @param string|null $utm_campaign
     */
    public function setUtmCampaign(?string $utm_campaign): void
    {
        $this->utm_campaign = $utm_campaign;
    }

    /**
     * @return string|null
     */
    public function getUtmTerm(): ?string
    {
        return $this->utm_term;
    }

    /**
     * @param string|null $utm_term
     */
    public function setUtmTerm(?string $utm_term): void
    {
        $this->utm_term = $utm_term;
    }

    /**
     * @return string|null
     */
    public function getAssetId(): ?string
    {
        return $this->asset_id;
    }

    /**
     * @param string|null $asset_id
     */
    public function setAssetId(?string $asset_id): void
    {
        $this->asset_id = $asset_id;
    }

    /**
     * @return string|null
     */
    public function getYearOfBirth(): ?string
    {
        return $this->year_of_birth;
    }

    /**
     * @param string|null $year_of_birth
     */
    public function setYearOfBirth(?string $year_of_birth): void
    {
        $this->year_of_birth = $year_of_birth;
    }

    /**
     * @return string|null
     */
    public function getAdId(): ?string
    {
        return $this->ad_id;
    }

    /**
     * @param string|null $ad_id
     */
    public function setAdId(?string $ad_id): void
    {
        $this->ad_id = $ad_id;
    }

    /**
     * @return string|null
     */
    public function getAdSetId(): ?string
    {
        return $this->ad_set_id;
    }

    /**
     * @param string|null $ad_set_id
     */
    public function setAdSetId(?string $ad_set_id): void
    {
        $this->ad_set_id = $ad_set_id;
    }

    /**
     * @return string|null
     */
    public function getCampaignId(): ?string
    {
        return $this->campaign_id;
    }

    /**
     * @param string|null $campaign_id
     */
    public function setCampaignId(?string $campaign_id): void
    {
        $this->campaign_id = $campaign_id;
    }

    /**
     * @return string|null
     */
    public function getAdName(): ?string
    {
        return $this->ad_name;
    }

    /**
     * @param string|null $ad_name
     */
    public function setAdName(?string $ad_name): void
    {
        $this->ad_name = $ad_name;
    }

    /**
     * @return string|null
     */
    public function getAdSetName(): ?string
    {
        return $this->ad_set_name;
    }

    /**
     * @param string|null $ad_set_name
     */
    public function setAdSetName(?string $ad_set_name): void
    {
        $this->ad_set_name = $ad_set_name;
    }

    /**
     * @return string|null
     */
    public function getAdPlacementPosition(): ?string
    {
        return $this->ad_placement_position;
    }

    /**
     * @param string|null $ad_placement_position
     */
    public function setAdPlacementPosition(?string $ad_placement_position): void
    {
        $this->ad_placement_position = $ad_placement_position;
    }

    /**
     * @return string|null
     */
    public function getSiteSourceName(): ?string
    {
        return $this->site_source_name;
    }

    /**
     * @param string|null $site_source_name
     */
    public function setSiteSourceName(?string $site_source_name): void
    {
        $this->site_source_name = $site_source_name;
    }

    /**
     * @return string|null
     */
    public function getUtmContent(): ?string
    {
        return $this->utm_content;
    }

    /**
     * @param string|null $utm_content
     */
    public function setUtmContent(?string $utm_content): void
    {
        $this->utm_content = $utm_content;
    }

    /**
     * @return string|null
     */
    public function getMarketplaceSearchTerm(): ?string
    {
        return $this->marketplace_search_term;
    }

    /**
     * @param string|null $marketplace_search_term
     */
    public function setMarketplaceSearchTerm(?string $marketplace_search_term): void
    {
        $this->marketplace_search_term = $marketplace_search_term;
    }

    /**
     * @return string|null
     */
    public function getMarketplaceSearchQueryId(): ?string
    {
        return $this->marketplace_search_query_id;
    }

    /**
     * @param string|null $marketplace_search_query_id
     */
    public function setMarketplaceSearchQueryId(?string $marketplace_search_query_id): void
    {
        $this->marketplace_search_query_id = $marketplace_search_query_id;
    }

    /**
     * Constructor
     * @param mixed $params
     */
    public function __construct($params = array())
    {
        if(is_numeric($params)){
            $params = DB::table((new Leads())->getTable())->where('id', $params)->first();

            if(is_null($params)){
                $params = array();
            }else{
                $params = (array)$params;
            }
        }

        $this->hydrate($params);
    }

    /**
     * Hydrate the lead type
     *
     * @param array|null $lead
     *
    */
    protected function hydrate(?array $lead){

        if(is_array($lead) && count($lead) > 0){
            foreach ($lead as $key => $value) {
                $method = 'set'.str_replace(" ","", ucwords(str_replace("_", " ", $key)));

                if (method_exists($this, $method)) {
                    $this->$method($value);
                }
            }
        }
    }
}
