<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Questions extends Model
{
    use HasFactory;

    /**
     * @var $table
    */
    protected $table = 'questions';

    /**
     * @var $hidden
    */
    protected $hidden = [
        'created_at',
        'updated_at'
    ];
}
