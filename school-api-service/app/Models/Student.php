<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Student extends Model
{
    use HasFactory;

    //set the table
    protected $table = 'students';

    //these fields are not mass assignable
    protected $guarded = [];


    //return the school relationship
    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    //return the class relationship
    public function studentClass(): BelongsTo
    {
        return $this->belongsTo(StudentClass::class,'class_id');
    }
    //return the stream relationship
    public function studentStream(): BelongsTo
    {
        return $this->belongsTo(StudentStream::class,'stream_id');
    }
}
