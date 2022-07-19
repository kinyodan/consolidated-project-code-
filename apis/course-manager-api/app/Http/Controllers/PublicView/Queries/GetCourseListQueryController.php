<?php
namespace App\Http\Controllers\PublicView\Queries;

use App\Http\Controllers\PublicView\CoursesPublicViewController;
use App\Http\Controllers\Traits\CanCache;

class GetCourseListQueryController
{
    use CanCache;

    /**
     * @var CoursesPublicViewController $coursesPublicViewController
     */
    protected CoursesPublicViewController $coursesPublicViewController;

    /**
     * Construct
     */
    public function __construct(CoursesPublicViewController $coursesPublicViewController)
    {
        $this->coursesPublicViewController = $coursesPublicViewController;
    }

    /**
     * Filter courses by country
    */
}
