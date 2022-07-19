<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseBulkImport extends Model
{
    /**
     * @var $table
    */
    protected $table = 'course_uploads';

    /**
     * @var $guarded
    */
    protected $guarded = [];

}
