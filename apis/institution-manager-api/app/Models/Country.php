<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    /**
     * @var $table
    */
    protected $table = "countries";

    /**
     * @var $guarded
    */
    protected $guarded = [];

    /**
     * @var $hidden
    */
    protected $hidden = [
        'is_blocked', 'slug',
        'is_blocked', 'is_deleted',
        'is_deleted', 'created_by',
        'updated_by', 'deleted_by',
        'deactivated_by', 'deleted_at',
        'deactivated_at', 'created_at',
        'updated_at'
    ];
}
