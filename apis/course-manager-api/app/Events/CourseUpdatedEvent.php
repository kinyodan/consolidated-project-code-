<?php
namespace App\Events;

use App\Models\Course;
use Illuminate\Queue\SerializesModels;

class CourseUpdatedEvent extends Event
{
    use SerializesModels;

    /**
     * @var string $course_code
    */
    public string $course_code;

    /**
     * Create a new event instance.
     *
     * @param string $course_code
     *
     * @return void
     */
    public function __construct(string $course_code)
    {
        $this->course_code = $course_code;
    }
}
