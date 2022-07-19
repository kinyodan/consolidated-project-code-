<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InstitutionHighlight extends Model
{
    use HasFactory;

    /**
     * @var string
    */
    protected $table = 'institution_highlights';

    /**
     * @var array $guarded
    */
    protected $guarded = [];

    /**
     * @var array $visible
    */
    protected $visible = [
        'id',
        'key_highlight',
        'key_highlight_description',
        'display_order'
    ];

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
