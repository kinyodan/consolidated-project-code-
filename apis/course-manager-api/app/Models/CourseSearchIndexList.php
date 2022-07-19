<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseSearchIndexList extends Model
{
    use HasFactory;

    /**
     * @var $table
    */
    protected $table = 'course_search_index_list';

    /**
     * Disable timestamp enforcement
    */
    public $timestamps = false;
}
