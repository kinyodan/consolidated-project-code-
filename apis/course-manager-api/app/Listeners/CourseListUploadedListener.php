<?php
namespace App\Listeners;

use App\Events\CoursesUploadedEvent;
use App\Http\Controllers\Traits\CanLog;
use App\Jobs\ProcessUploadedCourseListJob;
use App\Models\CourseBulkImport;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Events\Dispatcher;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CourseListUploadedListener
{
    use CanLog;

    public function handle(CoursesUploadedEvent $event)
    {
        try{
            dispatch(new ProcessUploadedCourseListJob())->onQueue('process_uploaded_courses_list');
        }catch (\Exception $exception){
            $this->logException($exception);
        }
    }
}
