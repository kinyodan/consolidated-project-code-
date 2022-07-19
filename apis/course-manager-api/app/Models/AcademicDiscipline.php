<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class AcademicDiscipline extends Model
{
    /**
     * @var $table
    */
    protected $table = 'academic_disciplines';

    /**
     * @var $guarded
    */
    protected $guarded = [];

    /**
     * @var $appends
    */
    protected $appends = [
        'course_count'
    ];

    /**
     * @var $visible
    */
    protected $visible = [
        'id',
        'discipline_code',
        'discipline_name',
        'course_count',
        'discipline_small_icon',
        'discipline_large_icon',
        'is_deleted',
        'seo_page_title',
        'seo_page_description',
        'seo_page_h1_title',
        'seo_page_keywords'
    ];

    /**
     * Courses
    */
    public function courses(): BelongsToMany
    {
        return $this->belongsToMany(
            Course::class,
            CourseAcademicDiscipline::class,
            'academic_disciplines_id',
            'courses_id'
        );
    }

    /**
     * Get published courses count
    */
    public function getCourseCountAttribute(): int
    {
        return $this->courses()
            ->whereNotNull('indexing_object_id')
            ->where('is_active', 1)
            ->count('courses_id');
    }

    /**
     * Get academic disciplines with published courses
    */
    public function scopeHasPublishedCourses($query){
        return $query->whereHas('courses', function ($courses){
            $courses
                ->where('is_active', 1)
                ->whereNotNull('indexing_object_id');
        });
    }

    /**
     * Scope a query to only include active users.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query
            ->where('status', 1)
            ->where('is_deleted', 0)
            ->orderBy('discipline_name');
    }
}
