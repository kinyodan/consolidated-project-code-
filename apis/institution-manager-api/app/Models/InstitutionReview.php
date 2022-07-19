<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InstitutionReview extends Model
{
    /**
     * @var $table
    */
    protected $table = 'institution_reviews';

    /**
     * @var $guarded
    */
    protected $guarded = [];

    /**
     * @var $visible
    */
    protected $visible = [
        'rated_by', 'rating_score',
        'published_on', 'is_published',
        'course_taken', 'graduation_year',
        'review'
    ];
}
