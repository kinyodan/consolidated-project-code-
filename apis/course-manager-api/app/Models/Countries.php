<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Countries extends Model
{
    /**
     * @var string $table
    */
    protected $table = 'countries';

    /**
     * @var array $guarded
    */
    protected $guarded = [];
}
