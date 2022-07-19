<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CareerPathway extends Model
{
    //
    protected $table = 'career_pathways';

    protected $fillable = [
        'name', 'email',
    ];


}
