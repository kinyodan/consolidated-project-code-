<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    /**
     * @var string $table
    */
    protected $table = 'settings';

    /**
     * @var array $guarded
    */
    protected $guarded = [];
}
