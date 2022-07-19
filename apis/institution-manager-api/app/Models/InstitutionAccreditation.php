<?php
namespace App\Models;

use App\Http\Controllers\Helpers\CraydelHelperFunctions;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string|null $institution_code
 * @property string|null $organization_name
 * @property string|null $organization_acronym
 * @property string|null $organization_image
 * @property string|null $accreditation_description
 * @property string|null $display_order
 * @property integer|null $is_active
 * @property integer|null $is_deleted
*/
class InstitutionAccreditation extends Model
{
    use HasFactory;

    /**
     * @var string $table
    */
    protected $table = 'institution_accreditations';

    /**
     * @var array $guarded
    */
    protected $guarded = [];

    /**
     * @var array $visible
    */
    protected $visible = [
        'id',
        'organization_name',
        'organization_acronym',
        'big_organization_image',
        'small_organization_image',
        'accreditation_description'
    ];

    /**
     * Scope a query to only include active users.
     *
     * @param Builder $query
     * @param string|null $institution_code
     * @return Builder
     */
    public function scopeActive(Builder $query, ?string $institution_code = null): Builder
    {
        $institution_code = CraydelHelperFunctions::toCleanString($institution_code);

        if(!empty($institution_code)){
            return $query
                ->where('is_active', 1)
                ->where('institution_code', $institution_code)
                ->where('is_deleted', 0);
        }

        return $query
            ->where('is_active', 1)
            ->where('is_deleted', 0);
    }
}
