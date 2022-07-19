<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JobTitle extends Model
{
    /**
     * @var string $table
    */
    protected $table = 'possible_job_titles';

    /**
     * @var array $guarded
    */
    protected $guarded = [];

    /**
     * @var array
    */
    protected $visible = [
        'category',
        'job_title',
        'description',
        'is_active'
    ];

    /**
     * Category
    */
    public function category(): BelongsTo
    {
        return $this->belongsTo(JobTitleCategory::class, 'job_title_category_id', 'id');
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
