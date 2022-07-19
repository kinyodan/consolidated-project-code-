<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class School extends Model
{
    use HasFactory;
  protected $table = 'schools';
  
  protected $guarded = [];
  
  public function country(): BelongsTo
  {
    return $this->belongsTo(Country::class, 'country_id');
  }
  public function curriculum(): BelongsTo
  {
    return $this->belongsTo(Curriculum::class, 'curriculum_id');
  }
}
