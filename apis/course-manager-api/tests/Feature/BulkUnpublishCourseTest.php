<?php
namespace Feature;

use App\Events\CourseCreatedEvent;
use App\Http\Controllers\Courses\Commands\PushCourseDataToSearchEngineCommandController;
use App\Http\Controllers\Helpers\LanguageTranslationHelper;
use App\Jobs\DeleteImageCDNJob;
use App\Jobs\UnpublishCourseFromSearchEngineJob;
use App\Jobs\UnpublishCourseIndexSearchEngineJob;
use App\Jobs\UploadImageToCDNJob;
use App\Models\BulkDelete;
use App\Models\Course;
use App\Models\CourseAcademicDiscipline;
use App\Models\CourseSearchIndexList;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Schema;
use TestCase;
use TestingHelperFunction;

class BulkUnpublishCourseTest extends TestCase
{
    use TestingHelperFunction;
	/**
	 * A basic test example.
	 *
	 * @return void
	 */
	public function testIfBulkUnpublishedCourseExist()
	{
        $response = $this->post('bulk-unpublish-course-details');
        $response->assertTrue(true);
	}
    public  function  testIfTheBulkCourseIsunPublished(){
        $course_codes=[];
        $this->withoutExceptionHandling();
        $token = file_get_contents(storage_path('test-token.txt'));
        $course=Course::factory()->count(2)->create();
        $course = $course->first();
        CourseAcademicDiscipline::factory()->count(2)->create(['courses_id'=>$course->id]);
        $courses= DB::table('courses')->get();
        foreach($courses as $course) {
            $course_codes[] = $course->course_code;
        }
        $course_codes=json_encode($course_codes);
        Queue::fake();
        $this->post('courses/admin/bulk-unpublish',['course_codes'=>$course_codes],[
            'token' => $token,
            'locale' => 'en'
        ])->seeJsonContains([
            'status' => true,
            'message' => LanguageTranslationHelper::translate('courses.success.bulk_unpublished')
        ])->seeInDatabase((new Course())->getTable(),[
            'course_code' => $course->course_code,
            'is_published' => 0,
            'is_active' => 0
        ]);

        static::assertCount(
            1,
            DB::table((new BulkDelete())->getTable())->where('course_code', $course->course_code)->get()
        );

        Schema::disableForeignKeyConstraints();
        DB::table((new Course())->getTable())->truncate();
        DB::table((new CourseAcademicDiscipline())->getTable())->truncate();
        DB::table((new CourseSearchIndexList())->getTable())->truncate();
        Schema::enableForeignKeyConstraints();

    }
}
