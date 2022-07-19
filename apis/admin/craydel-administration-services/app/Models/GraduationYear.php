<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GraduationYear extends Model
{
  /**
   * @var $table
   */
  protected $table = 'years';
  
  use HasFactory;
  
  /**
   * @var $guarded
   */
  protected $guarded = [];
}
