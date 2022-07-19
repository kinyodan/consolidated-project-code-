<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Leads extends Model
{
    use HasFactory;

    /**
     * @var $table
    */
    protected $table = 'leads';

    /**
     * @var $guarded
    */
    protected $guarded = [];

    /**
     * @var $hidden
    */
    protected $hidden = [];
}
