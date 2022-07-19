<?php
namespace Feature;

use App\Http\Controllers\Courses\Commands\PushCourseDataToSearchEngineCommandController;
use App\Http\Controllers\Helpers\LanguageTranslationHelper;
use App\Jobs\DeleteImageCDNJob;
use App\Jobs\UnpublishCourseFromSearchEngineJob;
use App\Models\Course;
use App\Models\CourseAcademicDiscipline;
use App\Models\CourseSearchIndexList;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Schema;
use TestCase;
use TestingHelperFunction;

/**
 *
 */
class DeleteCourseTest extends TestCase
{
    use TestingHelperFunction;

    /**
     * Setup tests
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->withoutExceptionHandling();
        Schema::disableForeignKeyConstraints();
        $db_name = DB::connection()->getDatabaseName();
        $tables = DB::select('SHOW TABLES');

        foreach($tables as $table){
            if ($table == 'migrations') {
                continue;
            }

            $table_name = $table->{"Tables_in_{$db_name}"};
            DB::table($table_name)->truncate();
        }

        Schema::enableForeignKeyConstraints();
        $this->artisan('db:seed');
    }

    /**
     * @throws Exception
     */
    public function testIfUserCanDeleteCourse()
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

        $this->post('courses/admin/' . $course->course_code . '/delete', [], [
            'token' => $token,
            'locale' => 'en'
        ])->seeJsonContains([
            'status' => true,
            'message' => LanguageTranslationHelper::translate('courses.success.deleted')
        ])->seeInDatabase((new Course())->getTable(), [
            'course_code' => $course->course_code,
            'is_deleted' => 1,
        ]);

        Queue::assertPushed(UnpublishCourseFromSearchEngineJob::class, function ($job) use ($course) {
            return $job->course_code == $course->course_code;
        });

        Queue::assertPushed(DeleteImageCDNJob::class);
        Schema::disableForeignKeyConstraints();
        DB::table((new Course())->getTable())->truncate();
        DB::table((new CourseAcademicDiscipline())->getTable())->truncate();
        DB::table((new CourseSearchIndexList())->getTable())->truncate();
        Schema::enableForeignKeyConstraints();
    }
}
