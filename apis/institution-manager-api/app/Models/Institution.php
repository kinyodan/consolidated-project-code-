<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Institution extends Model
{
    use HasFactory;

    /**
     * @var $table
    */
    protected $table = 'institutions';

    /**
     * @var $with
    */
    protected $with  = [
        'type'
    ];

    /**
     * @var $guarded
    */
    protected $guarded = [];

    /**
     * @var $timestamps
    */
    public $timestamps = false;

    /**
     * Hidden attributes
     *
     * @var $hidden
    */
    protected $hidden = [
        'created_at', 'deleted_at'
    ];

    /**
     * @var $appends
    */
    protected $appends = [
        'average_rating',
        'primary_image',
        'country_name',
        'institution_type_name'
    ];

    /**
     * Institution type
    */
    public function type(): BelongsTo
    {
        return $this->belongsTo(InstitutionType::class, 'institution_type', 'id');
    }

    /**
     * Country
    */
    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'country_code', 'iso_code');
    }

    /**
     * Reviews
    */
    public function reviews(): HasMany
    {
        return $this->hasMany(InstitutionReview::class, 'institution_code', 'institution_code');
    }

    /**
     * Accreditation
    */
    public function accreditations(): HasMany
    {
        return $this->hasMany(InstitutionAccreditation::class, 'institution_code', 'institution_code');
    }

    /**
     * Get average rating
    */
    public function getAverageRatingAttribute(){
        return $this->reviews()->avg('rating_score');
    }

    /**
     * Gallery
    */
    public function gallery(): HasMany
    {
        return $this
            ->hasMany(InstitutionGallery::class, 'institution_code', 'institution_code')
            ->where('is_deleted', 0)
            ->where(function ($q){
                return $q
                    ->whereNotNull('big_image_url')
                    ->orWhereNotNull('video_url');
            })
            ->orderBy('type', 'DESC')
            ->orderBy('is_featured', 'DESC')
            ->orderBy('asset_position');
    }

    /**
     * Get the institutions primary image
    */
    public function getPrimaryImageAttribute(){
        if(!is_null($this->gallery)){
            $featured_gallery_item = $this
                ->gallery()->take(1)
                ->where('is_featured', 1)
                ->where('type', 'Image')
                ->value('big_image_url');

            return !is_null($featured_gallery_item) ? $featured_gallery_item : $this->logo_url;
        }else{
            return null;
        }
    }

    /**
     * Get country name
    */
    public function getCountryNameAttribute(){
        if(isset($this->country)){
            return $this->country->name ?? null;
        }

        return null;
    }

    /**
     * Get institution type name
    */
    public function getInstitutionTypeNameAttribute(){
        if(isset($this->type) && !is_null($this->type())){
            return $this->type->name ?? null;
        }

        return null;
    }

    /**
     * Scope a query to only include active institutions.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', 1);
    }
}
