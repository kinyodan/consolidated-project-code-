<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class CourseAcademicDiscipline extends Pivot
{
    use HasFactory;

    /**
     * @var $table
    */
    protected $table = 'course_academic_discipline';

    /**
     * Disable timestamp enforcement
    */
    public $timestamps = false;

    /**
     * Course
    */
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Academic categories
    */
    public function academicCategory(): BelongsTo
    {
        return $this->belongsTo(AcademicDiscipline::class);
    }
}
