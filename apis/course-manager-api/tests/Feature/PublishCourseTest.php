<?php

namespace Feature;

use App\Events\CourseCreatedEvent;
use App\Events\CourseUpdatedEvent;
use App\Http\Controllers\Courses\Commands\PushCourseDataToSearchEngineCommandController;
use App\Http\Controllers\Helpers\LanguageTranslationHelper;
use App\Jobs\UploadImageToCDNJob;
use App\Models\Course;
use App\Models\CourseAcademicDiscipline;
use App\Models\CourseSearchIndexList;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Schema;
use TestCase;
use Illuminate\Support\Facades\Event;
use TestingHelperFunction;

class PublishCourseTest extends TestCase
{
    use TestingHelperFunction;

    public function testIfPublishCourseRouteExists()
    {
        $response = $this->post('publish-course-details');
        $response->assertTrue(true);
    }

    /**
     * @throws Exception
     */
    public function testIfUserCanPublishCourse()
    {
        $this->withoutExceptionHandling();
        $token = file_get_contents(storage_path('test-token.txt'));
        $course = Course::factory()->count(1)->create();
        $course = $course->first();
        CourseAcademicDiscipline::factory()->count(2)->create(['courses_id' => $course->id]);
        $this->artisan('search:course:generate-index-list');
        (new PushCourseDataToSearchEngineCommandController(
            $course->course_code
        ))->make()->push();

        Queue::fake();
        Event::fake();
        $this->post('courses/admin/' . $course->course_code . '/publish', [], [
            'token' => $token,
            'locale' => 'en'
        ])->seeJsonContains([
            'status' => true,
            'message' => LanguageTranslationHelper::translate('courses.success.published')
        ])->seeInDatabase((new Course())->getTable(), [
            'course_code' => $course->course_code,
            'is_published' => 1,
            'is_active' => 1,
            'should_unpublish' => 0
        ]);
        Queue::assertPushed(UploadImageToCDNJob::class);
        Event::assertDispatched(CourseCreatedEvent::class);

        Schema::disableForeignKeyConstraints();
        DB::table((new Course())->getTable())->truncate();
        DB::table((new CourseAcademicDiscipline())->getTable())->truncate();
        DB::table((new CourseSearchIndexList())->getTable())->truncate();
        Schema::enableForeignKeyConstraints();
    }
}
