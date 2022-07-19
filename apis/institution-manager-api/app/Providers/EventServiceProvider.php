<?php
namespace App\Providers;

use App\Listeners\InstitutionGalleryListener;
use App\Listeners\InstitutionListener;
use Laravel\Lumen\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The subscriber classes to register.
     *
     * @var array
     */
    protected $subscribe = [
        InstitutionListener::class,
        InstitutionGalleryListener::class
    ];
}
