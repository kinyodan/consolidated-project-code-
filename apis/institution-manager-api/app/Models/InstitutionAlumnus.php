<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class InstitutionAlumnus extends Model
{
    use HasFactory;

    /**
     * @var string $table
    */
    protected $table = 'institution_alumni';

    /**
     * @var array $guarded
    */
    protected $guarded = [];

    /**
     * @var array $visible
    */
    protected $visible = [
        'id',
        'alumni_name',
        'graduation_year',
        'course_taken',
        'current_employer',
        'current_position',
        'current_position_name',
        'personal_profile_url',
        'big_alumnus_image_path',
        'small_alumnus_image_path',
        'is_finished',
        'status',
        'question_step',
        'current_location',
        'email',
        'university_name',
        'institution_code',
        'course_type',
        'unique_url',
        'course_category',
        'review'
    ];

    /**
     * Appends
    */
    protected $appends = [
        'current_position_name'
    ];

    /**
     * Employment position
    */
    public function jobTitle(): BelongsTo
    {
        return $this->belongsTo(JobTitle::class, 'current_position');
    }

    /**
     * Get average rating
     */
    public function getCurrentPositionNameAttribute(){
        return isset($this->jobTitle) ? $this->jobTitle->job_title : null;
    }

    /**
     * Review
    */
    public function review(): HasOne
    {
        return $this->hasOne(Reviews::class, 'institution_alumni_id');
    }

    /**
     * Scope a query to only include active users.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', 1)->where('is_deleted', 0);
    }
}
