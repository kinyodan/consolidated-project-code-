<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InstitutionGallery extends Model
{
    use HasFactory;

    /**
     * @var $table
    */
    protected $table = 'institution_gallery';

    /**
     * @var $guard
    */
    protected $guarded = [];
}
