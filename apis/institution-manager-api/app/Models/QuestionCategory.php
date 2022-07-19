<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class QuestionCategory extends Model
{
    /**
     * @var $table
    */
    protected $table = 'question_categories';

    /**
     * @var $guarded
    */
    protected $guarded = [];

    /**
     * @var $hidden
    */
    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    /**
     * Questions
    */
    public function questions(): HasMany
    {
        return $this
            ->hasMany(Questions::class, 'question_category_id')
            ->where('is_published', 1)
            ->orderBy('order');
    }
}
