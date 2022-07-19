<?php

namespace Feature;

use App\Http\Controllers\Helpers\LanguageTranslationHelper;
use App\Models\Grade;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Schema;
use TestCase;
use Illuminate\Support\Facades\Event;
use TestingHelperFunction;

class GradesTest extends TestCase
{
    use TestingHelperFunction;

    public function testListingGradesRouteExists()
    {
        $response = $this->post('list-grades');
        $response->assertTrue(true);
    }

    public function testIfUserCanGetListOfGrades()
    {
        $this->withoutExceptionHandling();
        $token = file_get_contents(storage_path('test-token.txt'));
        $grade = Grade::factory()->count(2)->create();
        $grade = $grade->first();
        $this->post('courses/admin/list-grades/', [], [
            'token' => $token,
            'locale' => 'en'
        ])->seeJsonContains([
            'status' => true,
            'message' => LanguageTranslationHelper::translate('grades.success.listed')
        ])->seeInDatabase((new Grade())->getTable(), [
            'id' => $grade->id,
            'grade_equivalent' => $grade->grade_equivalent,
        ]);

        static::assertCount(
            1,
            DB::table((new Grade())->getTable())->where('id', $grade->id)->get()
        );

        Schema::disableForeignKeyConstraints();
        DB::table((new Grade())->getTable())->truncate();
        Schema::enableForeignKeyConstraints();
    }

    public function testIfUserCanUpdateAGrade()
    {
        $this->withoutExceptionHandling();
        $token = file_get_contents(storage_path('test-token.txt'));
        $grade = Grade::factory()->count(1)->create();
        $grade = $grade->first();
        $this->post('courses/admin/' . $grade->id . '/update-grade', ['country_id' => $grade->country_id, 'min' => $grade->min, 'max' => $grade->max, 'grade_equivalent' => $grade->grade_equivalent], [
            'token' => $token,
            'locale' => 'en'
        ])->seeJsonContains([
            'status' => true,
            'message' => LanguageTranslationHelper::translate('grades.success.is_updated')
        ])->seeInDatabase((new Grade())->getTable(), [
            'id' => $grade->id,
            'country_id' => $grade->country_id,
            'min' => $grade->min,
            'max' => $grade->max,
            'grade_equivalent' => $grade->grade_equivalent,
        ]);

        Schema::disableForeignKeyConstraints();
        DB::table((new Grade())->getTable())->truncate();
        Schema::enableForeignKeyConstraints();

    }

    public function testIfUserCanDeleteAGrade()
    {

        $this->withoutExceptionHandling();
        $token = file_get_contents(storage_path('test-token.txt'));
        $grade = Grade::factory()->count(2)->create();
        $grade = $grade->first();
        $this->post('courses/admin/' . $grade->id . '/delete-grade', [], [
            'token' => $token,
            'locale' => 'en'
        ])->seeJsonContains([
            'status' => true,
            'message' => LanguageTranslationHelper::translate('grades.success.is_deleted')
        ]);
        static::assertCount(
            0,
            DB::table((new Grade())->getTable())->where('id', $grade->id)->get()
        );

        Schema::disableForeignKeyConstraints();
        DB::table((new Grade())->getTable())->truncate();
        Schema::enableForeignKeyConstraints();

    }


}
