<?php

namespace Feature;

use App\Http\Controllers\Helpers\LanguageTranslationHelper;
use App\Models\CoursesPathway;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Schema;
use TestCase;
use Illuminate\Support\Facades\Event;
use TestingHelperFunction;

class CoursesPathwayTest extends TestCase
{
    use TestingHelperFunction;

    public function testListingCoursePathwayRouteExists()
    {
        $response = $this->post('list-courses-pathways');
        $response->assertTrue(true);
    }

    public function testIfUserCanListTheCoursePathways()
    {
        $this->withoutExceptionHandling();
        $token = file_get_contents(storage_path('test-token.txt'));
        $course_pathways = CoursesPathway::factory()->count(2)->create();
        $course_pathways = $course_pathways->first();
        $this->post('courses/admin/list-courses-pathways/', [], [
            'token' => $token,
            'locale' => 'en'
        ])->seeJsonContains([
            'status' => true,
            'message' => LanguageTranslationHelper::translate('pathways.success.listed')
        ])->seeInDatabase((new CoursesPathway())->getTable(), [
            'id' => $course_pathways->id,
            'is_published' => 1,
        ]);

        static::assertCount(
            1,
            DB::table((new CoursesPathway())->getTable())->where('id', $course_pathways->id)->get()
        );

        Schema::disableForeignKeyConstraints();
        DB::table((new CoursesPathway())->getTable())->truncate();
        Schema::enableForeignKeyConstraints();
    }

    public function testIfUserCanUpdateTheCoursePathways()
    {
        $this->withoutExceptionHandling();
        $token = file_get_contents(storage_path('test-token.txt'));
        $course_pathways = CoursesPathway::factory()->count(2)->create();
        $course_pathways = $course_pathways->first();
        $this->post('courses/admin/' . $course_pathways->id . '/update-courses-pathways', ['career_pathways_id' =>$course_pathways->career_pathways_id,'academic_disciplines_id'=>4], [
            'token' => $token,
            'locale' => 'en'
        ])->seeJsonContains([
            'status' => true,
            'message' => LanguageTranslationHelper::translate('pathways.success.is_updated')
        ])->seeInDatabase((new CoursesPathway())->getTable(), [
            'id' => $course_pathways->id,
            'academic_disciplines_id' => 4,
        ]);

        Schema::disableForeignKeyConstraints();
        DB::table((new CoursesPathway())->getTable())->truncate();
        Schema::enableForeignKeyConstraints();
    }

    public function testIfUserCanDeleteTheCoursePathways()
    {

        $this->withoutExceptionHandling();
        $token = file_get_contents(storage_path('test-token.txt'));
        $course_pathways = CoursesPathway::factory()->count(2)->create();
        $course_pathways = $course_pathways->first();

        $this->post('courses/admin/' . $course_pathways->id . '/delete-courses-pathways', [], [
            'token' => $token,
            'locale' => 'en'
        ])->seeJsonContains([
            'status' => true,
            'message' => LanguageTranslationHelper::translate('pathways.success.is_deleted')
        ]);
        static::assertCount(
            0,
            DB::table((new CoursesPathway())->getTable())->where('id', $course_pathways->id)->get()
        );

        Schema::disableForeignKeyConstraints();
        DB::table((new CoursesPathway())->getTable())->truncate();
        Schema::enableForeignKeyConstraints();

    }
    


}
