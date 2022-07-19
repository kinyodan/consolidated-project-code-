<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LearningMode extends Model
{
    /**
     * @var $table
    */
    protected $table = 'learning_modes';

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
