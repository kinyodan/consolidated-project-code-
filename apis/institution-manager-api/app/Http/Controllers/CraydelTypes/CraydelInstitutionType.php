<?php
namespace App\Http\Controllers\CraydelTypes;

use App\Http\Controllers\Helpers\CraydelHelperFunctions;
use App\Http\Controllers\Traits\CanLog;
use App\Models\Institution;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class CraydelInstitutionType
{
    use CanLog;

    /**
     * @var string $institution_code
    */
    private $institution_code;

    /**
     * @var string $logo_url
    */
    private $logo_url;

    /**
     * @var string $logo_url_small
    */
    private $logo_url_small;

    /**
     * @var string $institution_type
    */
    private $institution_type;

    /**
     * @var string $institution_name
    */
    private $institution_name;

    /**
     * @var string $institution_name_slug
    */
    private $institution_name_slug;

    /**
     * @var string $description
    */
    private $description;

    /**
     * @var string $profile_details
    */
    private $profile_details;

    /**
     * @var string $city
    */
    private $city;

    /**
     * @var mixed $country
    */
    private $country;

    /**
     * @var int $country_ranking
    */
    private $country_ranking;

    /**
     * @var int $regional_ranking
    */
    private $regional_ranking;

    /**
     * @var int $continental_ranking
    */
    private $continental_ranking;

    /**
     * @var int $global_ranking
    */
    private $global_ranking;

    /**
     * @vat int $system_internal_ranking
    */
    private $system_internal_ranking;

    /**
     * @var string $accredited_by
    */
    private $accredited_by;

    /**
     * @var string $accreditation_body_url
    */
    private $accreditation_body_url;

    /**
     * @var string $accredited_by_acronym
    */
    private $accredited_by_acronym;

    /**
     * @var string $ownership_type
    */
    private $ownership_type;

    /**
     * @var int $is_featured
    */
    private $is_featured;

    /**
     * @var string $primary_image
    */
    private $primary_image;

    /**
     * @var int|null $number_of_courses
    */
    private $number_of_courses;

    /**
     * @var array|null $academic_disciplines
    */
    private $academic_disciplines;

    /**
     * @return string
     */
    public function getInstitutionCode(): ?string
    {
        return $this->institution_code;
    }

    /**
     * @return string
     */
    public function getLogoUrl(): ?string
    {
        return $this->logo_url;
    }

    /**
     * @param string|null $logo_url
     */
    public function setLogoUrl(?string $logo_url): void
    {
        $this->logo_url = $logo_url;
    }

    /**
     * @return string
     */
    public function getLogoUrlSmall(): ?string
    {
        return $this->logo_url_small;
    }

    /**
     * @param string|null $logo_url_small
     */
    public function setLogoUrlSmall(?string $logo_url_small): void
    {
        $this->logo_url_small = $logo_url_small;
    }

    /**
     * @param string|null $institution_code
     */
    protected function setInstitutionCode(?string $institution_code): void
    {
        $this->institution_code = $institution_code;
    }

    /**
     * @return string
     */
    public function getInstitutionType(): ?string
    {
        return $this->institution_type;
    }

    /**
     * @param string|null $institution_type
     */
    protected function setInstitutionType(?string $institution_type): void
    {
        $this->institution_type = $institution_type;
    }

    /**
     * @return string|null
     */
    public function getInstitutionTypeName(): ?string
    {
        return isset($this->institution->type->name) ? $this->institution->type->name : null;
    }

    /**
     * @param string|null $institution_type_name
     */
    protected function setInstitutionTypeName(?string $institution_type_name): void
    {
        $institution_type_name1 = $institution_type_name;
    }

    /**
     * @return string
     */
    public function getInstitutionName(): ?string
    {
        return $this->institution_name;
    }

    /**
     * @param string|null $institution_name
     */
    protected function setInstitutionName(?string $institution_name): void
    {
        $this->institution_name = $institution_name;
    }

    /**
     * @return string
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string|null $description
     */
    protected function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getProfileDetails(): ?string
    {
        return $this->profile_details;
    }

    /**
     * @param string|null $profile_details
     */
    protected function setProfileDetails(?string $profile_details): void
    {
        $this->profile_details = $profile_details;
    }

    /**
     * @return string
     */
    public function getCity(): ?string
    {
        return $this->city;
    }

    /**
     * @param string|null $city
     */
    protected function setCity(?string $city): void
    {
        $this->city = $city;
    }

    /**
     * @return object|null
     */
    public function getCountry(): ?object
    {
        if(is_array($this->country)){
            return (object)$this->country;
        }

        return is_object($this->country) ? $this->country : null;
    }

    /**
     * @param mixed $country
     */
    protected function setCountry($country): void
    {
        $this->country = $country;
    }

    /**
     * @return mixed|null
     */
    public function getCountryName()
    {
        return isset($this->institution->country->name) ? $this->institution->country->name : null;
    }

    /**
     * @param string|null $country_name
     */
    public function setCountryName(?string $country_name): void
    {
        $country_name1 = $country_name;
    }

    /**
     * @return string
     */
    public function getInstitutionNameSlug(): ?string
    {
        return $this->institution_name_slug;
    }

    /**
     * @param string|null $institution_name_slug
     */
    public function setInstitutionNameSlug(?string $institution_name_slug): void
    {
        $this->institution_name_slug = $institution_name_slug;
    }

    /**
     * @return int
     */
    public function getCountryRanking(): ?int
    {
        return isset($this->country_ranking) ? intval($this->country_ranking) : null;
    }

    /**
     * @param string|null $country_ranking
     */
    protected function setCountryRanking(?string $country_ranking): void
    {
        $this->country_ranking = intval($country_ranking);
    }

    /**
     * @return int
     */
    public function getRegionalRanking(): ?int
    {
        return isset($this->regional_ranking) ? intval($this->regional_ranking) : null;
    }

    /**
     * @param string|null $regional_ranking
     */
    public function setRegionalRanking(?string $regional_ranking): void
    {
        $this->regional_ranking = CraydelHelperFunctions::toNumbers($regional_ranking);
    }

    /**
     * @return int
     */
    public function getContinentalRanking(): ?int
    {
        return isset($this->continental_ranking) ? intval($this->continental_ranking) : null;
    }

    /**
     * @param string|null $continental_ranking
     */
    public function setContinentalRanking(?string $continental_ranking): void
    {
        $this->continental_ranking = CraydelHelperFunctions::toNumbers($continental_ranking);
    }

    /**
     * @return int
     */
    public function getGlobalRanking(): ?int
    {
        return isset($this->global_ranking) ? intval($this->global_ranking) : null;
    }

    /**
     * @param string|null $global_ranking
     */
    public function setGlobalRanking(?string $global_ranking): void
    {
        $this->global_ranking = CraydelHelperFunctions::toNumbers($global_ranking);
    }

    /**
     * @return mixed
     */
    public function getSystemInternalRanking()
    {
        return $this->system_internal_ranking;
    }

    /**
     * @param mixed $system_internal_ranking
     */
    public function setSystemInternalRanking($system_internal_ranking): void
    {
        $this->system_internal_ranking = !empty($system_internal_ranking) ? $system_internal_ranking : 0;
    }

    /**
     * @return string
     */
    public function getAccreditedBy(): ?string
    {
        return $this->accredited_by;
    }

    /**
     * @param string|null $accredited_by
     */
    public function setAccreditedBy(?string $accredited_by): void
    {
        $this->accredited_by = $accredited_by;
    }

    /**
     * @return string
     */
    public function getAccreditationBodyUrl(): ?string
    {
        return $this->accreditation_body_url;
    }

    /**
     * @param string|null $accreditation_body_url
     */
    public function setAccreditationBodyUrl(?string $accreditation_body_url): void
    {
        $this->accreditation_body_url = $accreditation_body_url;
    }

    /**
     * @return string
     */
    public function getAccreditedByAcronym(): ?string
    {
        return $this->accredited_by_acronym;
    }

    /**
     * @param string|null $accredited_by_acronym
     */
    public function setAccreditedByAcronym(?string $accredited_by_acronym): void
    {
        $this->accredited_by_acronym = $accredited_by_acronym;
    }

    /**
     * @var Institution $institution
    */
    protected $institution;

    /**
     * @return string
     */
    public function getOwnershipType(): ?string
    {
        return $this->ownership_type;
    }

    /**
     * @param string|null $ownership_type
     */
    public function setOwnershipType(?string $ownership_type): void
    {
        $this->ownership_type = $ownership_type;
    }

    /**
     * @return int
     * @throws GuzzleException
     */
    public function getNumberOfCourses(): ?int
    {
        $client = new Client();

        $url = sprintf(
            config('services.craydel_services.courses_manager.endpoints.get_institution_course_count'),
            $this->institution_code
        );

        if(!empty($url)){
            $result = json_decode($client->get($url)->getBody()->getContents());

            if(isset($result->status) && isset($result->data->course_count)){
                return $result->data->course_count;
            }
        }

        return 0;
    }

    /**
     * @param int|null $number_of_courses
     */
    public function setNumberOfCourses(?int $number_of_courses): void
    {
        $this->number_of_courses = $number_of_courses;
    }

    /**
     * @return array|null
     * @throws GuzzleException
     */
    public function getAcademicDisciplines(): ?array
    {
        $client = new Client();

        $url = sprintf(
            config('services.craydel_services.courses_manager.endpoints.get_institution_course_categories'),
            $this->institution_code
        );

        if(!empty($url)){
            $result = json_decode($client->get($url)->getBody()->getContents());

            if(isset($result->status) && isset($result->data->academic_disciplines)){
                return $result->data->academic_disciplines;
            }
        }

        return array();
    }

    /**
     * @param array|null $academic_disciplines
     */
    public function setAcademicDisciplines(?array $academic_disciplines): void
    {
        $this->academic_disciplines = $academic_disciplines;
    }

    /**
     * @return int
     */
    public function getIsFeatured(): ?int
    {
        $is_featured = $this->is_featured ?? 0;

        return $is_featured == 1 ? 1 : 0;
    }

    /**
     * @param int|null $is_featured
     */
    public function setIsFeatured(?int $is_featured): void
    {
        $this->is_featured = $is_featured;
    }

    /**
     * @return string
     */
    public function getPrimaryImage(): ?string
    {
        return isset($this->primary_image) && !empty($this->primary_image) ? $this->primary_image : null;
    }

    /**
     * @param string|null $primary_image
     */
    public function setPrimaryImage(?string $primary_image): void
    {
        $this->primary_image = $primary_image;
    }

    /**
     * Constructor
     * @param string|null $institution_code
     */
    public function __construct(?string $institution_code = null)
    {
        if(!is_null($institution_code)){
            $this->institution_code = $institution_code;
        }

        $this->institution = Institution::with(['type', 'country'])
            ->where(
            'institution_code', $this->institution_code
            )->first();

        $this->hydrate();
    }

    /**
     * Hydrate the institution
    */
    protected function hydrate(){
        try{
            if(is_null($this->institution)){
                throw new \Exception('Unable to hydrate the institution object.');
            }

            foreach ($this->institution->toArray() as $key => $value) {
                $method = 'set'.str_replace(" ","", ucwords(str_replace("_", " ", $key)));

                if (method_exists($this, $method)) {
                    $this->$method($value);
                }
            }
        }catch (\Exception $exception){
            $this->logException($exception);
        }
    }
}
