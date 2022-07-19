<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobTitleCategory extends Model
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
        'job_title_category',
        'description',
        'is_active'
    ];
}
