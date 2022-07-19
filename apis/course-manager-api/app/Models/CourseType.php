<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseType extends Model
{
    /**
     * @var $table
    */
    protected $table = 'course_types';

    /**
     * @var $guarded
    */
    protected $guarded = [];

    /**
     * @var $visible
    */
    protected $visible = [
        'id', 'name'
    ];
}
