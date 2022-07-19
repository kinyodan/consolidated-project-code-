<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InstitutionType extends Model
{
    /**
     * @var $table
    */
    protected $table = "institution_types";

    /**
     * @var $guarded
    */
    protected $guarded = [];

    /**
     * @var $visible
    */
    protected $visible = [
        'id', 'name'
    ];
}
