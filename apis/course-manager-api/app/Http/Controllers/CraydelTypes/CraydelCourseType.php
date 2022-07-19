<?php
namespace App\Http\Controllers\CraydelTypes;

use App\Http\Controllers\Helpers\AcademicDisciplineHelper;
use App\Http\Controllers\Helpers\CountryHelper;
use App\Http\Controllers\Helpers\CourseTypesHelper;
use App\Http\Controllers\Helpers\InstitutionsHelper;
use App\Http\Controllers\Traits\CanLog;
use App\Models\Course;
use App\Models\CourseType;
use App\Models\LearningMode;
use Illuminate\Support\Facades\DB;

class CraydelCourseType
{
    use CanLog;

    /**
     * @var string|null $url_course_slug
     */
    protected ?string $url_course_slug;

    /**
     * @var string|null $course_name
     */
    private ?string $course_name;

    /**
     * @var string|null $course_name_slug
     */
    private ?string $course_name_slug;

    /**
     * @var string|null $institution
     */
    private ?string $institution;

    /**
     * @var $institution_ranking
     */
    private $institution_ranking;

    /**
     * @var $institution_continent
     */
    private $institution_continent;

    /**
     * @var $course_rating
     */
    private $course_rating;

    /**
     * @var string|null $accredited_by
     */
    private ?string $accredited_by;

    /**
     * @var string|null $accredited_by_acronym
     */
    private ?string $accredited_by_acronym;

    /**
     * @var string|null $accreditation_organization_url
     */
    private ?string $accreditation_organization_url;

    /**
     * @var string|null $description
     */
    private ?string $description;

    /**
     * @var string|null $country
     */
    private ?string $country = null;

    /**
     * @var string|null $course_overview
     */
    private ?string $course_overview;

    /**
     * @var string|null $discipline
     */
    private ?string $discipline;

    /**
     * @var string|null $course_type
     */
    private ?string $course_type;

    /**
     * @var string|null $graduate_level
     */
    private ?string $graduate_level;

    /**
     * @var string|null $attendance_type
     */
    private ?string $attendance_type;

    /**
     * @var string|null $course_requirements
     */
    private ?string $course_requirements;

    /**
     * @var string|null $currency
     */
    private ?string $currency;

    /**
     * @var float|null $standard_fee_payable
     */
    private ?float $standard_fee_payable;

    /**
     * @var string|null $standard_fee_billing_type
    */
    private ?string $standard_fee_billing_type;

    /**
     * @var string|null $course_small_image
     */
    private ?string $course_small_image;

    /**
     * @var string|null $course_image
     */
    private ?string $course_image;

    /**
     * @var string|null $course_structure_breakdown
     */
    private ?string $course_structure_breakdown;

    /**
     * @var string|null $course_duration
     */
    private ?string $course_duration;

    /**
     * @var string|null $course_duration_category
     */
    private ?string $course_duration_category;

    /**
     * @var float|null $standard_fee_payable_usd
     */
    private ?float $standard_fee_payable_usd;

    /**
     * @var float|null $foreign_student_fee_payable_usd
    */
    private ?float $foreign_student_fee_payable_usd;

    /**
     * @var string|null $foreign_student_first_year_fee_payable_usd
    */
    private ?string $foreign_student_first_year_fee_payable_usd;

    /**
     * @var string|null $standard_first_year_fee_payable_usd
    */
    private ?string $standard_first_year_fee_payable_usd;

    /**
     * @var $course
     */
    private $course;

    /**
     * @var string|null $course_code
     */
    private ?string $course_code;

    /**
     * @var string|null $institution_code
     */
    private ?string $institution_code;

    /**
     * @var string|null $country_code
     */
    private ?string $country_code;

    /**
     * @var bool $has_error
     */
    private bool $has_error;

    /**
     * @var array|null $search_engine_object
     */
    private ?array $search_engine_object;

    /**
     * @var string|null $learning_mode
     */
    private ?string $learning_mode;

    /**
     * @var string|null $enrollment_details
     */
    private ?string $enrollment_details;

    /**
     * @var string|null $discipline_code
     */
    private ?string $discipline_code;

    /**
     * @var string|null $maximum_scholarship_available
     */
    private ?string $maximum_scholarship_available;

    /**
     * @var int|null $is_featured
     */
    private ?int $is_featured;

    /**
     * @var float|null $popularity
    */
    private ?float $popularity;

    /**
     * @return string
     */
    public function getUrlCourseSlug(): ?string
    {
        return $this->url_course_slug;
    }

    /**
     * @param string|null $url_course_slug
     */
    public function setUrlCourseSlug(?string $url_course_slug): void
    {
        $this->url_course_slug = $this->course_code.'-'.$this->course_name_slug;
    }

    /**
     * @return ?string
     */
    public function getCourseName(): ?string
    {
        return $this->course_name;
    }

    /**
     * @param string|null $course_name
     */
    public function setCourseName(?string $course_name): void
    {
        $this->course_name = $course_name;
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
     * @return string
     */
    public function getAccreditationOrganizationUrl(): ?string
    {
        return $this->accreditation_organization_url;
    }

    /**
     * @param string|null $accreditation_organization_url
     */
    public function setAccreditationOrganizationUrl(?string $accreditation_organization_url): void
    {
        $this->accreditation_organization_url = $accreditation_organization_url;
    }

    /**
     * @return mixed
     */
    public function getCourseRating()
    {
        return $this->course_rating;
    }

    /**
     * @param mixed $course_rating
     */
    public function setCourseRating($course_rating): void
    {
        $this->course_rating = $course_rating;
    }

    /**
     * @return string
     */
    public function getCourseNameSlug(): ?string
    {
        return $this->course_name_slug;
    }

    /**
     * @param string|null $course_name_slug
     */
    public function setCourseNameSlug(?string $course_name_slug): void
    {
        $this->course_name_slug = $course_name_slug;
    }

    /**
     * @return string
     */
    public function getInstitution(): ?string
    {
        return $this->institution;
    }

    /**
     * @param string|null $institution
     */
    public function setInstitution(?string $institution): void
    {
        $this->institution = $institution;
    }

    /**
     * @return mixed
     */
    public function getInstitutionRanking()
    {
        return $this->institution_ranking;
    }

    /**
     * @param mixed $institution_ranking
     */
    public function setInstitutionRanking($institution_ranking): void
    {
        $this->institution_ranking = $institution_ranking;
    }

    /**
     * @return mixed
     */
    public function getInstitutionContinent()
    {
        return $this->institution_continent;
    }

    /**
     * @param mixed $institution_continent
     */
    public function setInstitutionContinent($institution_continent): void
    {
        $this->institution_continent = $institution_continent;
    }

    /**
     * @return string
     */
    public function getDescription(): ?string
    {
        return str_replace(array("\n", "\r"), ' ', $this->description);
    }

    /**
     * @param string|null $description
     */
    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getCountry(): ?string
    {
        return $this->country;
    }

    /**
     * @param string|null $country
     */
    public function setCountry(?string $country): void
    {
        $this->country = CountryHelper::getName($country);
    }

    /**
     * @return ?string
     */
    public function getCourseOverview(): ?string
    {
        return str_replace(array("\n", "\r"), ' ', $this->course_overview);
    }

    /**
     * @param string|null $course_overview
     */
    public function setCourseOverview(?string $course_overview): void
    {
        $this->course_overview = $course_overview;
    }

    /**
     * @return string
     */
    public function getDisciplineCode(): ?string
    {
        return $this->discipline_code;
    }

    /**
     * @param string|null $discipline_code
     */
    public function setDisciplineCode(?string $discipline_code): void
    {
        $this->discipline_code = $discipline_code;
    }

    /**
     * @return string
     */
    public function getDiscipline(): ?string
    {
        if(is_numeric($this->discipline_code)){
            $this->discipline = AcademicDisciplineHelper::getDisciplineNameByID($this->discipline_code);
        }else{
            $this->discipline = AcademicDisciplineHelper::getDisciplineNameByCode($this->discipline_code);
        }

        return $this->discipline;
    }

    /**
     * @param string|null $discipline
     */
    public function setDiscipline(?string $discipline): void
    {
        $this->discipline = $discipline;
    }

    /**
     * @return string
     */
    public function getCourseType(): ?string
    {
        if(is_numeric($this->course_type)){
            $course_type = DB::table((new CourseType())->getTable())->where('id', $this->course_type)->first(['name']);

            if(isset($course_type->name) && !empty($course_type->name)){
                return $course_type->name;
            }
        }

        return $this->course_type;
    }

    /**
     * @param string|null $course_type
     */
    public function setCourseType(?string $course_type): void
    {
        $this->course_type = $course_type;
    }

    /**
     * @return string
     */
    public function getGraduateLevel(): ?string
    {
        return $this->graduate_level;
    }

    /**
     * @param string|null $graduate_level
     */
    public function setGraduateLevel(?string $graduate_level): void
    {
        $this->graduate_level = $graduate_level;
    }

    /**
     * @return string
     */
    public function getAttendanceType(): ?string
    {
        return $this->attendance_type;
    }

    /**
     * @param string|null $attendance_type
     */
    public function setAttendanceType(?string $attendance_type): void
    {
        $this->attendance_type = $attendance_type;
    }

    /**
     * @return string
     */
    public function getLearningMode(): ?string
    {
        $learning_mode = DB::table((new LearningMode())->getTable())
            ->where('id', intval($this->learning_mode))
            ->first(['name']);

        return isset($learning_mode->name) && !empty($learning_mode->name) ? $learning_mode->name : null;
    }

    /**
     * @param string|null $learning_mode
     */
    public function setLearningMode(?string $learning_mode): void
    {
        $this->learning_mode = $learning_mode;
    }

    /**
     * @return string
     */
    public function getEnrollmentDetails(): ?string
    {
        return $this->enrollment_details;
    }

    /**
     * @param string|null $enrollment_details
     */
    public function setEnrollmentDetails(?string $enrollment_details): void
    {
        $this->enrollment_details = $enrollment_details;
    }

    /**
     * @return string
     */
    public function getCourseRequirements(): ?string
    {
        return $this->course_requirements;
    }

    /**
     * @param string|null $course_requirements
     */
    public function setCourseRequirements(?string $course_requirements): void
    {
        $this->course_requirements = $course_requirements;
    }

    /**
     * @return string
     */
    public function getCurrency(): ?string
    {
        return $this->currency;
    }

    /**
     * @param string|null $currency
     */
    public function setCurrency(?string $currency): void
    {
        $this->currency = $currency;
    }

    /**
     * @return float
     */
    public function getStandardFeePayable(): ?float
    {
        return $this->standard_fee_payable;
    }

    /**
     * @param float|null $standard_fee_payable
     */
    public function setStandardFeePayable(?float $standard_fee_payable): void
    {
        $this->standard_fee_payable = $standard_fee_payable;
    }

    /**
     * @return string
     */
    public function getCourseSmallImage(): ?string
    {
        return $this->course_small_image;
    }

    /**
     * @param string|null $course_small_image
     */
    public function setCourseSmallImage(?string $course_small_image): void
    {
        $this->course_small_image = $course_small_image;
    }

    /**
     * @return string
     */
    public function getCourseImage(): ?string
    {
        return $this->course_image;
    }

    /**
     * @param string|null $course_image
     */
    public function setCourseImage(?string $course_image): void
    {
        $this->course_image = $course_image;
    }

    /**
     * @return string
     */
    public function getCourseStructureBreakdown(): ?string
    {
        return str_replace(array("\n", "\r"), ' ', $this->course_structure_breakdown);
    }

    /**
     * @param string|null $course_structure_breakdown
     */
    public function setCourseStructureBreakdown(?string $course_structure_breakdown): void
    {
        $this->course_structure_breakdown = $course_structure_breakdown;
    }

    /**
     * @return string
     */
    public function getCourseDuration(): ?string
    {
        return $this->course_duration;
    }

    /**
     * @param string|null $course_duration
     */
    public function setCourseDuration(?string $course_duration): void
    {
        $this->course_duration = $course_duration;
    }

    /**
     * @return string
     */
    public function getCourseDurationCategory(): ?string
    {
        return $this->course_duration_category;
    }

    /**
     * @param string|null $course_duration_category
     */
    public function setCourseDurationCategory(?string $course_duration_category): void
    {
        $this->course_duration_category = $course_duration_category;
    }

    /**
     * @return float
     */
    public function getStandardFeePayableUsd(): ?float
    {
        return $this->standard_fee_payable_usd;
    }

    /**
     * @param float|null $standard_fee_payable_usd
     */
    public function setStandardFeePayableUsd(?float $standard_fee_payable_usd): void
    {
        $this->standard_fee_payable_usd = $standard_fee_payable_usd;
    }

    /**
     * @return float|null
     */
    public function getForeignStudentFeePayableUsd(): ?float
    {
        return $this->foreign_student_fee_payable_usd;
    }

    /**
     * @return string|null
     */
    public function getForeignStudentFirstYearFeePayableUsd(): ?float
    {
        return $this->foreign_student_first_year_fee_payable_usd;
    }

    /**
     * @param string|null $foreign_student_first_year_fee_payable_usd
     */
    public function setForeignStudentFirstYearFeePayableUsd(?string $foreign_student_first_year_fee_payable_usd): void
    {
        $this->foreign_student_first_year_fee_payable_usd = $foreign_student_first_year_fee_payable_usd;
    }

    /**
     * @return float|null
     */
    public function getStandardFirstYearFeePayableUsd(): ?float
    {
        return $this->standard_first_year_fee_payable_usd;
    }

    /**
     * @param string|null $standard_first_year_fee_payable_usd
     */
    public function setStandardFirstYearFeePayableUsd(?string $standard_first_year_fee_payable_usd): void
    {
        $this->standard_first_year_fee_payable_usd = $standard_first_year_fee_payable_usd;
    }

    /**
     * @param float|null $foreign_student_fee_payable_usd
     */
    public function setForeignStudentFeePayableUsd(?float $foreign_student_fee_payable_usd): void
    {
        $this->foreign_student_fee_payable_usd = $foreign_student_fee_payable_usd;
    }

    /**
     * @return string
     */
    public function getCourseCode(): ?string
    {
        return $this->course_code;
    }

    /**
     * @return string
     */
    public function getInstitutionCode(): ?string
    {
        return $this->institution_code;
    }

    /**
     * @param string|null $institution_code
     */
    public function setInstitutionCode(?string $institution_code): void
    {
        $this->institution_code = $institution_code;
    }

    /**
     * @param string|null $course_code
     */
    public function setCourseCode(?string $course_code): void
    {
        $this->course_code = $course_code;
    }

    /**
     * @return string
     */
    public function getCountryCode(): ?string
    {
        return $this->country_code;
    }

    /**
     * @param string|null $country_code
     */
    public function setCountryCode(?string $country_code): void
    {
        $this->country_code = $country_code;
    }

    /**
     * @return string
     */
    public function getMaximumScholarshipAvailable(): ?string
    {
        return $this->maximum_scholarship_available;
    }

    /**
     * @param string|null $maximum_scholarship_available
     */
    public function setMaximumScholarshipAvailable(?string $maximum_scholarship_available): void
    {
        $this->maximum_scholarship_available = $maximum_scholarship_available;
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
     * @return void
     */
    public function setIsFeatured(?int $is_featured): void
    {
        $this->is_featured = $is_featured;
    }

    /**
     * @return string
     */
    public function getStandardFeeBillingType(): ?string
    {
        return $this->standard_fee_billing_type;
    }

    /**
     * @return float|null
     */
    public function getPopularity(): ?float
    {
        return $this->popularity;
    }

    /**
     * @param float|null $popularity
     * @return void
     */
    public function setPopularity(?float $popularity): void
    {
        $this->popularity = $popularity;
    }

    /**
     * @param string|null $standard_fee_billing_type
     * @return CraydelCourseType
     */
    public function setStandardFeeBillingType(?string $standard_fee_billing_type): CraydelCourseType
    {
        $this->standard_fee_billing_type = $standard_fee_billing_type;
        return $this;
    }

    /**
     * Constructor
     * @param string|null $course_code
     */
    public function __construct(?string $course_code)
    {
        $this->course = DB::table((new Course())->getTable())
            ->where('course_code', trim($course_code))
            ->first();

        $this->hydrate();
    }

    /**
     * Hydrate
     */
    private function hydrate()
    {
        try{
            $data = $this->course;

            foreach ($data as $key => $value) {
                $method = 'set'.str_replace(" ","", ucwords(str_replace("_", " ", $key)));

                if (method_exists($this, $method)) {
                    $this->$method($value);
                }
            }

            $institution_summary = InstitutionsHelper::summary($this->course->institution_code);

            if(isset($institution_summary->data)){
                $this->setInstitution($institution_summary->data->institution_name ?? null);
                $this->setInstitutionRanking($institution_summary->data->continental_ranking ?? null);

                if(isset($institution_summary->data->country)){
                    $this->setInstitutionContinent($institution_summary->data->country['continent'] ?? null);
                    $this->setCountry($institution_summary->data->country['name'] ?? null);
                }
            }

            $this->setGraduateLevel($this->course->graduate_level);
            $this->setUrlCourseSlug($this->url_course_slug = $this->course_code.'-'.$this->course_name_slug);
            $this->setDiscipline(AcademicDisciplineHelper::getDisciplineNameByCode($this->course->discipline_code));
            $this->setCourseType(CourseTypesHelper::getCourseTypeNameByID($this->course_type));
            $this->setLearningMode($this->learning_mode);
        }catch (\Exception $exception){
            $this->logException($exception);
        }
    }
}
