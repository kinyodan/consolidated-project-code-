<?php
namespace App\Models;

use App\Http\Controllers\Helpers\EnrollmentDateHelper;
use App\Http\Controllers\Helpers\InstitutionsHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Course extends Model
{
    use HasFactory;

    /**
     * @var $table
    */
    protected $table = 'courses';

    /**
     * @var $guarded
    */
    protected $guarded = [];

    /**
     * @var $hidden
    */
    protected $hidden = [
        'is_picked_for_indexing', 'time_picked_for_indexing',
        'time_taken_to_index', 'indexing_error', 'has_updates',
        'indexing_object_id', 'created_by', 'updated_by',
        'approved_by', 'deleted_by', 'created_at', 'updated_at',
        'approved_at', 'deleted_at'
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'institution_summary',
        'discipline_name',
        'current_intake',
        'description_plain_text'
    ];

    /**
     * Course Type
    */
    public function type(): BelongsTo
    {
        return $this->belongsTo(CourseType::class, 'course_type', 'id');
    }

    /**
     * Course discipline
    */
    public function discipline(): BelongsTo
    {
        return $this->belongsTo(AcademicDiscipline::class, 'discipline_code', 'id');
    }

    /**
     * Link disciplines
    */
    public function linkedDisciplines(): ?belongsToMany
    {
        return $this->belongsToMany(
            AcademicDiscipline::class,
            CourseAcademicDiscipline::class,
            'courses_id',
            'academic_disciplines_id'
        );
    }

    /**
     * Learning mode
    */
    public function learningMode(): BelongsTo
    {
        return $this->belongsTo(LearningMode::class, 'learning_mode', 'id');
    }

    /**
     * Get the course discipline
    */
    public function getDisciplineNameAttribute(){
        return $this->discipline->discipline_name ?? null;
    }

    /**
     * Get institution summary
    */
    public function getInstitutionSummaryAttribute(){
        if(!isset($this->institution_code)){
            return null;
        }

        $summary = InstitutionsHelper::summary($this->institution_code);

        if(!isset($summary->data)){
            return null;
        }

        $summary = $summary->data;

        return call_user_func(function () use($summary){
            return [
                'institution_name' => $summary->institution_name ?? "",
                'description' => $summary->description ?? "",
                'city' => $summary->city ?? "",
                'email_address' => $summary->email_address ?? "",
                'academic_office_phone_number' => $summary->academic_office_phone_number ?? "",
                'academic_office_email_address' => $summary->academic_office_email_address ?? "",
                'academic_office_postal_address' => $summary->academic_office_postal_address ?? "",
                'university_postal_address' => $summary->university_postal_address ?? "",
                'seo_keywords' => $summary->seo_keywords ?? "",
                'seo_description' => $summary->seo_description ?? "",
                'system_internal_ranking' => $summary->system_internal_ranking ?? "",
                'country_ranking' => $summary->country_ranking ?? "",
                'regional_ranking' => $summary->regional_ranking ?? "",
                'continental_ranking' => $summary->continental_ranking ?? "",
                'global_ranking' => $summary->global_ranking ?? "",
                'accredited_by_acronym' => $summary->accredited_by_acronym ?? "",
                'date_registered' => $summary->date_registered ?? "",
                'accredited_by' => $summary->accredited_by ?? "",
                'accreditation_body_url' => $summary->accreditation_body_url ?? "",
                'website_url' => $summary->website_url ?? "",
                'logo_url' => $summary->logo_url ?? "",
                'logo_url_small' => $summary->logo_url_small ?? "",
                'inquiry_form_url' => $summary->inquiry_form_url ?? "",
                'finance_office_phone_number' => $summary->finance_office_phone_number ?? "",
                'finance_office_email_address' => $summary->finance_office_email_address ?? "",
                'country' => [
                    'continent' => $summary->country['continent'] ?? "",
                    'geographical_region' => $summary->country['geographical_region'] ?? "",
                    'iso_code' => $summary->country['iso_code'] ?? "",
                    'timezone' => $summary->country['timezone'] ?? "",
                    'phone_code' => $summary->country['phone_code'] ?? "",
                    'currency_code' => $summary->country['currency_code'] ?? "",
                    'currency_name' => $summary->country['currency_name'] ?? "",
                    'name' => $summary->country['name'] ?? "",
                ]
            ];
        });
    }

    /**
     * Set the course enrollment details
     *
     * @param string|null $value
     * @return string|null
     */
    public function getEnrollmentDetailsAttribute(?string $value): ?string
    {
        return EnrollmentDateHelper::make($value);
    }

    /**
     * Get current intake
    */
    public function getCurrentIntakeAttribute(): ?string{
        if(isset($this->enrollment_details) && !empty($this->enrollment_details)){
            return collect(json_decode($this->enrollment_details))->first();
        }

        return null;
    }

    /**
     * Get description plain text
     */
    public function getDescriptionPlainTextAttribute(): ?string{
        if(isset($this->description) && !empty($this->description)){
            return preg_replace('/\s+/', ' ', str_replace(array('&nbsp;'), array(' '), strip_tags($this->description)));
        }

        return null;
    }
}
