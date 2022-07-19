<?php

namespace App\Events;

use Illuminate\Queue\SerializesModels;

class CoursesUploadedEvent extends Event
{
    use SerializesModels;

    public function __construct()
    {

    }
}
