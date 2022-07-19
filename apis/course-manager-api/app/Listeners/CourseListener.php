<?php
namespace App\Listeners;

use App\Events\CourseApplicationLeadSubmittedToCRMEvent;
use App\Events\CourseCreatedEvent;
use App\Events\CourseUpdatedEvent;
use App\Http\Controllers\Courses\Commands\UpdateCourseStaticsCommandController;
use App\Http\Controllers\Traits\CanCache;
use App\Http\Controllers\Traits\CanLog;
use App\Jobs\GenerateCourseSearchIndexListJob;
use App\Jobs\UploadImageToCDNJob;
use Exception;
use Illuminate\Events\Dispatcher;

class CourseListener
{
    use CanLog, CanCache;

    /**
     * Course has been created
     *
     * @param CourseCreatedEvent $event
     * @return void
     */
    public function onCourseCreated(CourseCreatedEvent $event){
        try{
            $course_code = $event->course_code;

            if(empty($course_code)){
                throw new Exception('Invalid course code.');
            }

            dispatch(new UploadImageToCDNJob($course_code))->onQueue('upload_images_to_cdn');

            self::clearCache('academic_disciplines_with_courses');
        }catch (Exception $exception){
            $this->logException($exception);
        }
    }

    /**
     * Course has been updated
     *
     * @param CourseUpdatedEvent $event
     * @return void
     */
    public function onCourseUpdated(CourseUpdatedEvent $event){
        try{
            $course_code = $event->course_code;

            if(empty($course_code)){
                throw new Exception('Invalid course code.');
            }

            dispatch(new GenerateCourseSearchIndexListJob(
                $course_code
            ))->onQueue('generate_course_index_list');

            self::clearCache('academic_disciplines_with_courses');
        }catch (Exception $exception){
            $this->logException($exception);
        }
    }

    /**
     * Update the course statics after a lead associated with a course is submitted
     * @param CourseApplicationLeadSubmittedToCRMEvent $event
     * @throws Exception
     */
    public function onCourseApplicationLeadSubmittedToCRMEvent(CourseApplicationLeadSubmittedToCRMEvent $event){
        $this->logMessage("Updating the course submitted lead count.");

        (new UpdateCourseStaticsCommandController())->updateLeadCountAfterLeadIsSubmittedToCRM($event->crm_lead_id);
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param Dispatcher $events
     * @return void
     */
    public function subscribe(Dispatcher $events)
    {
        $events->listen(
            CourseCreatedEvent::class,
            [CourseListener::class, 'onCourseCreated']
        );

        $events->listen(
            CourseUpdatedEvent::class,
            [CourseListener::class, 'onCourseUpdated']
        );

        $events->listen(
            CourseApplicationLeadSubmittedToCRMEvent::class,
            [CourseListener::class, 'onCourseApplicationLeadSubmittedToCRMEvent']
        );
    }
}
