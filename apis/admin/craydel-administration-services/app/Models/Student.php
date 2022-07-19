<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Student extends Model
{
  use HasFactory;
  
  /**
   * @var  $table
   */
  protected $table = 'students';
  
  /**
   * @var $guarded
   */
  protected $guarded = [];
  
  /**
   * Get the student's class
   */
  public function class(): BelongsTo
  {
    return $this->belongsTo(UniversalSchoolClass::class,'class_id');
  }
  
  /**
   * Get the student's stream
   */
  public function stream(): BelongsTo
  {
    return $this->belongsTo(SchoolStream::class,'stream_id');
  }
  
  /**
   * Curriculum
   */
  public function curriculum(): BelongsTo
  {
    return $this->belongsTo(Curriculum::class, 'curriculum_id');
  }
  
  /**
   * Year
   */
  public function year(): BelongsTo
  {
    return $this->belongsTo(GraduationYear::class, 'year_id');
  }
  
  /**
   * Year
   */
  public function school(): BelongsTo
  {
    return $this->belongsTo(School::class, 'school_id');
  }
}
