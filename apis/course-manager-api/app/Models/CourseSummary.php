<?php
namespace App\Models;

use App\Http\Controllers\Helpers\EnrollmentDateHelper;
use App\Http\Controllers\Helpers\InstitutionsHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class CourseSummary extends Model
{
    use HasFactory;

    /**
     * @var $table
    */
    protected $table = 'course_enrollment_summary';

    /**
     * @var $guarded
    */
    protected $guarded = [];

    /**
     * @var $hidden
    */

}
