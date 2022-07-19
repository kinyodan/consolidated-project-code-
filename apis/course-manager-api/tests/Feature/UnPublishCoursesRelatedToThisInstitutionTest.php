<?php

namespace Feature;

use App\Http\Controllers\Courses\Commands\PushCourseDataToSearchEngineCommandController;
use App\Http\Controllers\Helpers\LanguageTranslationHelper;
use App\Models\Course;
use App\Models\CourseAcademicDiscipline;
use App\Models\CourseSearchIndexList;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Schema;
use TestCase;
use TestingHelperFunction;

class UnPublishCoursesRelatedToThisInstitutionTest extends TestCase
{
    use TestingHelperFunction;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testIfUnPublishCourseRelatedToInstitutionRouteExist()
    {
        $response = $this->post('unpublish-courses-related-to-this-institution');
        $response::assertTrue(true);
    }

    /**
     * @return void
     */
    public function testIfUserCanPublishCoursesRelatedToInstitution()
    {
        Schema::disableForeignKeyConstraints();
        DB::table((new Course())->getTable())->truncate();
        DB::table((new CourseAcademicDiscipline())->getTable())->truncate();
        DB::table((new CourseSearchIndexList())->getTable())->truncate();
        Schema::enableForeignKeyConstraints();
        $this->withoutExceptionHandling();
        $token = file_get_contents(storage_path('test-token.txt'));
        $course = Course::factory()->count(3)->create();
        $course = $course->first();
        $response = $this->post('courses/rpc/unpublish-courses-related-to-this-institution/', [
            'institution_code' => $course->institution_code
        ], [
            'token' => $token,
            'locale' => 'en'
        ])->seeJsonContains([
            'status' => true,
            'message' => LanguageTranslationHelper::translate('courses.success.unpublished')
        ])->seeInDatabase((new Course())->getTable(), [
            'course_code' => $course->course_code,
            'is_published' => 0,
            'is_active' => 0,
            'should_unpublish' => 1

        ]);
    }
}
