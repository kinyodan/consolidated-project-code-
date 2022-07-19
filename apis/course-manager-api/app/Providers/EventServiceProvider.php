<?php
namespace App\Providers;

use App\Events\CoursesUploadedEvent;
use App\Listeners\CourseApplicationWorkflowListener;
use App\Listeners\CourseListener;
use App\Listeners\CourseListUploadedListener;
use Laravel\Lumen\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        CoursesUploadedEvent::class => [
            CourseListUploadedListener::class
        ],
    ];

    /**
     * The subscriber classes to register.
     *
     * @var array
     */
    protected $subscribe = [
        CourseListener::class,
        CourseApplicationWorkflowListener::class
    ];
}
