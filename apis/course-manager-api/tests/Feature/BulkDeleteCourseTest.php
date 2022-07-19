<?php
namespace Feature;

use App\Http\Controllers\Courses\Commands\PushCourseDataToSearchEngineCommandController;
use App\Http\Controllers\Helpers\LanguageTranslationHelper;
use App\Jobs\DeleteImageCDNJob;
use App\Jobs\UnpublishCourseFromSearchEngineJob;
use App\Jobs\UnpublishCourseIndexSearchEngineJob;
use App\Models\BulkDelete;
use App\Models\Course;
use App\Models\CourseAcademicDiscipline;
use App\Models\CourseSearchIndexList;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Schema;
use TestCase;
use TestingHelperFunction;

class BulkDeleteCourseTest extends TestCase
{

    use TestingHelperFunction;
	/**
	 * A basic test example.
	 *
	 * @return void
	 */
	public function testIfBulkDeleteRouteExists()
	{
        $response = $this->post('bulk-delete-course-details');
        $response->assertTrue(true);
	}

    public  function  testIfTheBulkCourseDeleted(){
        $course_codes=[];
        $this->withoutExceptionHandling();
        $token = file_get_contents(storage_path('test-token.txt'));
        $course=Course::factory()->count(2)->create();
        $course = $course->first();
        CourseAcademicDiscipline::factory()->count(2)->create(['courses_id'=>$course->id]);
        $courses= DB::table('courses')->where('is_deleted','=',0)->get();
        foreach($courses as $course) {
            $course_codes[] = $course->course_code;
        }
        $course_codes=json_encode($course_codes);
        Queue::fake();
        $this->post('courses/admin/bulk-delete',['course_codes'=>$course_codes],[
            'token' => $token,
            'locale' => 'en'
        ])->seeJsonContains([
            'status' => true,
            'message' => LanguageTranslationHelper::translate('courses.success.bulk_deleted')
        ])->seeInDatabase((new Course())->getTable(),[
            'course_code' => $course->course_code,
            'is_flagged_deletion' => 1
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
