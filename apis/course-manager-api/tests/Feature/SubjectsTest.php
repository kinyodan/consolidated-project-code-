<?php
namespace Feature;

use App\Http\Controllers\Helpers\LanguageTranslationHelper;
use App\Models\DisplaySubjects;
use App\Models\EducationType;
use App\Models\Subject;
use Exception;
use Faker\Factory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Schema;
use TestCase;
use Illuminate\Support\Facades\Event;
use TestingHelperFunction;

class SubjectsTest extends TestCase
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

    public function testListingSubjectsRouteExists()
    {
        $response = $this->post('courses/admin/list-subjects');
        $response->assertTrue(true);
    }

    public function testIfUserCanGetListOfSubjects()
    {
        $this->withoutExceptionHandling();
        $token = file_get_contents(storage_path('test-token.txt'));
        $subject = Subject::factory()->count(2)->create();
        $subject = $subject->first();
        $this->post('courses/admin/list-subjects/', [], [
            'token' => $token,
            'locale' => 'en'
        ])->seeJsonContains([
            'status' => true,
            'message' => LanguageTranslationHelper::translate('subjects.success.listed')
        ])->seeInDatabase((new Subject())->getTable(), [
            'id' => $subject->id,
            'is_published' => 1,
        ]);

        static::assertCount(
            1,
            DB::table((new Subject())->getTable())->where('id', $subject->id)->get()
        );

        Schema::disableForeignKeyConstraints();
        DB::table((new Subject())->getTable())->truncate();
        Schema::enableForeignKeyConstraints();
    }

    public function testIfUserCanUpdateASubject()
    {
        $this->withoutExceptionHandling();
        $token = file_get_contents(storage_path('test-token.txt'));
        $subject = Subject::factory()->count(1)->create();
        $subject = $subject->first();
        $this->post('courses/admin/' . $subject->id . '/update-subject', ['subject_name' => 'English','country_id' => $subject->country_id], [
            'token' => $token,
            'locale' => 'en'
        ])->seeJsonContains([
            'status' => true,
            'message' => LanguageTranslationHelper::translate('subjects.success.is_updated')
        ])->seeInDatabase((new Subject())->getTable(), [
            'id' => $subject->id,
            'subject_name' => 'English',
        ]);

        Schema::disableForeignKeyConstraints();
        DB::table((new Subject())->getTable())->truncate();
        Schema::enableForeignKeyConstraints();

    }

    public function testIfUserCanDeleteASubject()
    {
        $this->withoutExceptionHandling();
        $token = file_get_contents(storage_path('test-token.txt'));
        $subject = Subject::factory()->count(2)->create();
        $subject = $subject->first();
        $this->post('courses/admin/' . $subject->id . '/delete-subject', [], [
            'token' => $token,
            'locale' => 'en'
        ])->seeJsonContains([
            'status' => true,
            'message' => LanguageTranslationHelper::translate('subjects.success.is_deleted')
        ]);
        static::assertCount(
            0,
            DB::table((new Subject())->getTable())->where('id', $subject->id)->get()
        );

        Schema::disableForeignKeyConstraints();
        DB::table((new Subject())->getTable())->truncate();
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Test if can return subjects when the users does not select an education system
    */
    public function testIfCanReturnSubjectsWhenTheUsersDoesNotSelectAnEducationSystem(){
        $this->withoutExceptionHandling();

        $education_system = EducationType::factory()->create([
            'country_id' => 113
        ])->first();

        DisplaySubjects::factory()->create([
            'country_id' => 113,
            'education_type_id' => $education_system->id,
            'academic_disciplines_id' => 1
        ]);

        $this->post('courses/rpc/list-display-subjects/1',[
            'country_code' => 'ke'
        ],[])
            ->seeJsonContains([
                'status' => true,
                'message' => LanguageTranslationHelper::translate('display_subjects.success.listed')
            ])->seeJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'subject_title',
                        'display_order'
                    ]
                ]
            ]);
    }

    /**
     * Test if can return subjects when the users selects an education system not in the same country
    */
    public function testIfCanReturnSubjectsWhenTheUsersSelectsOneOfTheGlobalEducationSystems(){
        $this->withoutExceptionHandling();

        $education_system = EducationType::factory()->create([
            'id' => 12,
            'country_id' => 1
        ])->first();

        DisplaySubjects::factory()->create([
            'country_id' => 113,
            'education_type_id' => $education_system->id,
            'academic_disciplines_id' => 1
        ]);

        $this->post('courses/rpc/list-display-subjects/1',[
            'country_code' => 'ke',
            'education_type_id' => 12
        ],[])
            ->seeJsonContains([
                'status' => true,
                'message' => LanguageTranslationHelper::translate('display_subjects.success.listed')
            ])->seeJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'subject_title',
                        'display_order'
                    ]
                ]
            ]);
    }

    /**
     * Test if can return subjects when they send an education system ID
    */
    public function testIfCanReturnSubjectsWhenTheUsersSelectsASpecificEducationsSystemInHerCountry(){
        $this->withoutExceptionHandling();

        EducationType::factory()->create([
            'country_id' => 113
        ]);

        DisplaySubjects::factory()->count(10)->create([
            'country_id' => 113,
            'education_type_id' => 1,
            'academic_disciplines_id' => 1
        ]);

        $this->post('courses/rpc/list-display-subjects/1',[
            'country_code' => 'ke',
            'education_type_id' => 1
        ],[])
            ->seeJsonContains([
                'status' => true,
                'message' => LanguageTranslationHelper::translate('display_subjects.success.listed')
            ])->seeJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'subject_title',
                        'display_order'
                    ]
                ]
            ]);
    }
}
