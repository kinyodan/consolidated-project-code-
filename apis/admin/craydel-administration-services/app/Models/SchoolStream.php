<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolStream extends Model
{
  use HasFactory;
  
  //set the table
  protected $table = 'streams';
  
  //these fields are not mass assignable
  protected $guarded = [];
}
