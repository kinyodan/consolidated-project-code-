<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    use HasFactory;

    //set the table
    protected $table = 'schools';

    //these fields are not mass assignable
    protected $guarded = [];

}
