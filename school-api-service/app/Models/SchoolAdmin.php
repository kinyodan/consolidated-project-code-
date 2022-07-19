<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SchoolAdmin extends Model
{
    use HasFactory;

    //set the table
    protected $table = 'school_admins';

    //these fields are not mass assignable
    protected $guarded = [];


    //return the school relationship
    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class,'school_id');
    }
}
