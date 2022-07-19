<?php
namespace Feature;

use App\Http\Controllers\Helpers\CountryHelper;
use App\Http\Controllers\Helpers\LanguageTranslationHelper;
use App\Models\EducationType;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use TestCase;
use TestingHelperFunction;

class EducationTypeTest extends TestCase
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

    public function testIfListingEducationTypeRouteExists()
    {
        $response = $this->post('courses/admin/list-education-types');
        $response->assertTrue(true);
    }

    public function testIfUserCanUpdateAnEducationType()
    {
        $this->withoutExceptionHandling();
        $token = file_get_contents(storage_path('test-token.txt'));
        $education = EducationType::factory()->count(1)->create();
        $education = $education->first();
        $this->post('courses/admin/' . $education->id . '/update-education-type', ['education_type_name' => 'English','country_id' => $education->country_id], [
            'token' => $token,
            'locale' => 'en'
        ])->seeJsonContains([
            'status' => true,
            'message' => LanguageTranslationHelper::translate('educations.success.is_updated')
        ])->seeInDatabase((new EducationType())->getTable(), [
            'id' => $education->id,
            'education_type_name' => 'English',
        ]);

        Schema::disableForeignKeyConstraints();
        DB::table((new EducationType())->getTable())->truncate();
        Schema::enableForeignKeyConstraints();

    }

    public function testIfUserCanDeleteAEducationType()
    {
        $this->withoutExceptionHandling();
        $token = file_get_contents(storage_path('test-token.txt'));
        $education = EducationType::factory()->count(1)->create();
        $education = $education->first();
        $this->post('courses/admin/' . $education->id . '/delete-education-type', [], [
            'token' => $token,
            'locale' => 'en'
        ])->seeJsonContains([
            'status' => true,
            'message' => LanguageTranslationHelper::translate('educations.success.is_deleted')
        ]);
        static::assertCount(
            0,
            DB::table((new EducationType())->getTable())->where('id', $education->id)->get()
        );

        Schema::disableForeignKeyConstraints();
        DB::table((new EducationType())->getTable())->truncate();
        Schema::enableForeignKeyConstraints();

    }

    public function testIfUserCanGetListOfEducation()
    {
        $this->withoutExceptionHandling();
        $token = file_get_contents(storage_path('test-token.txt'));
        $education = EducationType::factory()->count(2)->create();
        $education = $education->first();
        $this->post('courses/admin/list-education-types/', [], [
            'token' => $token,
            'locale' => 'en'
        ])->seeJsonContains([
            'status' => true,
            'message' => LanguageTranslationHelper::translate('educations.success.listed')
        ])->seeInDatabase((new EducationType())->getTable(), [
            'id' => $education->id,
            'is_published' => 1,
        ]);

        static::assertCount(
            1,
            DB::table((new EducationType())->getTable())->where('id', $education->id)->get()
        );

        Schema::disableForeignKeyConstraints();
        DB::table((new EducationType())->getTable())->truncate();
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Get education types based on the country code
    */
    public function testIfUserCanGetEducationTypeBasedOnTheCountryCode()
    {
        $this->withoutExceptionHandling();
        EducationType::factory()->count(1)->create([
            'education_type_name' => 'KCSE',
            'country_id' => CountryHelper::getIDBasedOnCode('KE')
        ]);

        $response = $this->get('courses/rpc/get-country-education-systems/ke')
            ->seeJsonContains([
                'status' => true,
                'message' => 'Listed'
            ])->seeJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'education_type_name'
                    ]
                ]
            ]);

        $response = json_decode($response->response->getContent());
        $data = $response->data;

        /*$this->assertTrue(collect($data)->contains('education_type_name', 'IB'));*/
        $this->assertTrue(collect($data)->contains('education_type_name', 'IGCSE / A-level'));
        $this->assertTrue(collect($data)->contains('education_type_name', 'KCSE'));
    }
}
