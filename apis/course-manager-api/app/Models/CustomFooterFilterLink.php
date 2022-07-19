<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class CustomFooterFilterLink extends Model
{
    /**
     * @var $table
    */
    protected $table = 'custom_footer_filter_links';

    /**
     * @var
    */
    protected $guarded = [];

    /**
     * Scope a query to only include active users.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', 1);
    }
}
