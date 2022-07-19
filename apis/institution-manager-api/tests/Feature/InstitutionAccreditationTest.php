<?php
namespace Feature;

use App\Http\Controllers\Helpers\CraydelHelperFunctions;
use App\Http\Controllers\Helpers\JWTHelper;
use App\Http\Controllers\Helpers\LanguageTranslationHelper;
use App\Jobs\DeleteImagesFromTheCDN;
use App\Jobs\UploadInstitutionAccreditationImageToCDNJob;
use App\Models\Institution;
use App\Models\InstitutionAccreditation;
use Carbon\Carbon;
use Faker\Factory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use TestCase;
use TestingHelperFunction;

class InstitutionAccreditationTest extends TestCase
{
    use TestingHelperFunction;

    /**
     * Test if user can add the institution's accreditation
    */
    public function testIfUserCanAddTheInstitutionAccreditation(){
        $this->withoutExceptionHandling();
        Schema::disableForeignKeyConstraints();
        DB::table((new Institution())->getTable())->truncate();
        DB::table((new InstitutionAccreditation())->getTable())->truncate();
        Schema::enableForeignKeyConstraints();

        $institution_code = Str::random(10);
        $file = Factory::create()->image(storage_path(), 1500, 1000);

        Institution::factory()->count(1)->create([
            'institution_code' => $institution_code,
            'is_featured' => 1
        ]);

        $token = file_get_contents(storage_path('test-token.txt'));
        $user = JWTHelper::decode($token);
        $organization_name = Factory::create()->text;
        $accreditation_description = Factory::create()->text;

        $uploadFile = new UploadedFile(
            $file,
            basename($file),
            'image/png',
            null,
            true
        );

        $server = $this->transformHeadersToServerVars(['token' => $token, 'locale' => 'en']);

        $payload = [
            'organization_name' => $organization_name,
            'organization_acronym' => CraydelHelperFunctions::makeAcronym($organization_name),
            'accreditation_description' => $accreditation_description,
            'created_at' => Carbon::now()->toDateTime(),
            'updated_at' => Carbon::now()->toDateTime()
        ];

        Queue::fake();

        $response = $this->call('POST',
            'institutions/admin/'.$institution_code.'/accreditations/add',
            $payload
            ,[
                'token' => $token,
                'locale' => 'en'
            ],[
                'organization_image' => $uploadFile
            ],$server);

        Queue::assertPushed(UploadInstitutionAccreditationImageToCDNJob::class);

        $this->assertJson($response->getContent());
        $response = json_decode($response->getContent());

        $this->assertObjectHasAttribute('status', $response);
        $this->assertObjectHasAttribute('message', $response);
        $this->assertEquals(true, $response->status);
        $this->assertEquals( LanguageTranslationHelper::translate('institutions.success.accreditation_saved'), $response->message);

        $this->seeInDatabase((new InstitutionAccreditation())->getTable(),[
            'organization_name' => $payload['organization_name'],
            'organization_acronym' => $payload['organization_acronym'],
            'accreditation_description' => $payload['accreditation_description'],
            'is_active' => 1,
            'is_deleted' => 0,
            'created_by' => $user->email,
            'updated_by' => $user->email
        ]);

        $accreditation = InstitutionAccreditation::all()->first();
        $this->assertNotNull($accreditation->temp_image_path);

        Schema::disableForeignKeyConstraints();
        DB::table((new Institution())->getTable())->truncate();
        DB::table((new InstitutionAccreditation())->getTable())->truncate();
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Test if user can get a list of the institution's accreditations
    */
    public function testIfUserCanGetAListOfTheInstitutionAccreditation(){
        $this->withoutExceptionHandling();
        Schema::disableForeignKeyConstraints();
        DB::table((new Institution())->getTable())->truncate();
        DB::table((new InstitutionAccreditation())->getTable())->truncate();
        Schema::enableForeignKeyConstraints();

        $institution_code = Str::random(10);

        Institution::factory()->count(1)->create([
            'institution_code' => $institution_code,
            'is_featured' => 1
        ]);

        InstitutionAccreditation::factory()->count(5)->create([
            'institution_code' => $institution_code
        ]);

        $token = file_get_contents(storage_path('test-token.txt'));

        $response = $this->get('institutions/admin/'.$institution_code.'/accreditations',
            [
                'token' => $token,
                'locale' => 'en'
            ])
            ->seeJsonContains([
                'status' => true,
                'message' => LanguageTranslationHelper::translate('institutions.success.accreditation_listed')
            ]);

        $response = $response->response->getContent();

        $this->assertJson($response);
        $response = json_decode($response);

        $this->assertIsObject($response);
        $this->assertObjectHasAttribute('data', $response);
        $this->assertObjectHasAttribute('items', $response->data);
        $first_accreditation = $response->data->items[0] ?? null;

        $this->assertObjectHasAttribute('organization_name', $first_accreditation);
        $this->assertObjectHasAttribute('organization_acronym', $first_accreditation);
        $this->assertObjectHasAttribute('big_organization_image', $first_accreditation);
        $this->assertObjectHasAttribute('small_organization_image', $first_accreditation);
        $this->assertObjectHasAttribute('accreditation_description', $first_accreditation);
        $this->assertNotNull($first_accreditation->organization_name);
        $this->assertNotNull($first_accreditation->organization_acronym);
        $this->assertNotNull($first_accreditation->accreditation_description);
        $this->assertNotNull($first_accreditation->big_organization_image);
        $this->assertNotNull($first_accreditation->small_organization_image);

        Schema::disableForeignKeyConstraints();
        DB::table((new Institution())->getTable())->truncate();
        DB::table((new InstitutionAccreditation())->getTable())->truncate();
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Test if user can get the accreditation details
    */
    public function testIfUserCabGetTheAccreditationDetails(){
        $this->withoutExceptionHandling();
        Schema::disableForeignKeyConstraints();
        DB::table((new Institution())->getTable())->truncate();
        DB::table((new InstitutionAccreditation())->getTable())->truncate();
        Schema::enableForeignKeyConstraints();

        Institution::factory()->count(1)->create([
            'institution_code' => $this->default_institution_code,
            'is_featured' => 1
        ]);

        $accreditation = InstitutionAccreditation::factory()->count(1)->create([
            'institution_code' => $this->default_institution_code
        ]);

        $token = file_get_contents(storage_path('test-token.txt'));

        $this->get('institutions/admin/'.$this->default_institution_code.'/accreditations/'.$accreditation->first()->id, [
            'token' => $token,
            'locale' => 'en'
        ])->seeJsonContains([
            'status' => true,
            'message' => LanguageTranslationHelper::translate('institutions.success.accreditation_shown')
        ])->seeJsonStructure([
            'data' => [
                'accreditation' => [
                    'organization_name',
                    'organization_acronym',
                    'big_organization_image',
                    'small_organization_image',
                    'accreditation_description'
                ]
            ]
        ]);

        Schema::disableForeignKeyConstraints();
        DB::table((new Institution())->getTable())->truncate();
        DB::table((new InstitutionAccreditation())->getTable())->truncate();
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Test if user can update the institution accreditation
    */
    public function testIfUserCanUpdateTheInstitutionAccreditation(){
        $this->withoutExceptionHandling();
        Schema::disableForeignKeyConstraints();
        DB::table((new Institution())->getTable())->truncate();
        DB::table((new InstitutionAccreditation())->getTable())->truncate();
        Schema::enableForeignKeyConstraints();

        $institution_code = Str::random(10);
        $file = Factory::create()->image(storage_path(), 1500, 1000);

        Institution::factory()->count(1)->create([
            'institution_code' => $institution_code,
            'is_featured' => 1
        ]);

        $accreditation = InstitutionAccreditation::factory()->count(1)->create([
            'institution_code' => $institution_code
        ]);

        $token = file_get_contents(storage_path('test-token.txt'));
        $user = JWTHelper::decode($token);
        $organization_name = Factory::create()->text;
        $accreditation_description = Factory::create()->text;

        $uploadFile = new UploadedFile(
            $file,
            basename($file),
            'image/png',
            null,
            true
        );

        $server = $this->transformHeadersToServerVars(['token' => $token, 'locale' => 'en']);

        $payload = [
            'organization_name' => $organization_name,
            'organization_acronym' => CraydelHelperFunctions::makeAcronym($organization_name),
            'accreditation_description' => $accreditation_description,
            'created_at' => Carbon::now()->toDateTime(),
            'updated_at' => Carbon::now()->toDateTime()
        ];

        Bus::fake();

        $accreditation_id = $accreditation->first()->id;
        $response = $this->call('POST',
            'institutions/admin/'.$institution_code.'/accreditations/'.$accreditation_id.'/update',
            $payload
            ,[
                'token' => $token,
                'locale' => 'en'
            ],[
                'organization_image' => $uploadFile
            ],$server);

        Bus::assertDispatched(DeleteImagesFromTheCDN::class);
        Bus::assertDispatched(UploadInstitutionAccreditationImageToCDNJob::class, function ($job) use($accreditation_id){
            return $job->accreditation_id == $accreditation_id;
        });

        $this->assertJson($response->getContent());
        $response = json_decode($response->getContent());

        $this->assertObjectHasAttribute('status', $response);
        $this->assertObjectHasAttribute('message', $response);
        $this->assertEquals(true, $response->status);
        $this->assertEquals( LanguageTranslationHelper::translate('institutions.success.accreditation_updated'), $response->message);

        $this->seeInDatabase((new InstitutionAccreditation())->getTable(),[
            'id' => $accreditation_id,
            'organization_name' => $payload['organization_name'],
            'organization_acronym' => $payload['organization_acronym'],
            'accreditation_description' => $payload['accreditation_description'],
            'updated_by' => $user->email
        ]);

        Schema::disableForeignKeyConstraints();
        DB::table((new Institution())->getTable())->truncate();
        DB::table((new InstitutionAccreditation())->getTable())->truncate();
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Test if user can delete the institution accreditation
    */
    public function testIfUserCanDeleteTheInstitutionAccreditation(){
        $this->withoutExceptionHandling();
        Schema::disableForeignKeyConstraints();
        DB::table((new Institution())->getTable())->truncate();
        DB::table((new InstitutionAccreditation())->getTable())->truncate();
        Schema::enableForeignKeyConstraints();

        $institution_code = Str::random(10);

        Institution::factory()->count(1)->create([
            'institution_code' => $institution_code,
            'is_featured' => 1
        ]);

        $accreditation = InstitutionAccreditation::factory()->count(1)->create([
            'institution_code' => $institution_code
        ]);

        $token = file_get_contents(storage_path('test-token.txt'));

        Queue::fake();

        $accreditation_id = $accreditation->first()->id;

        $response = $this->post(
            'institutions/admin/'.$institution_code.'/accreditations/'.$accreditation_id.'/delete', [],
            [
                'token' => $token,
                'locale' => 'en'
            ]
        );

        Queue::assertPushed(DeleteImagesFromTheCDN::class);

        $this->assertJson($response->response->getContent());
        $response = json_decode($response->response->getContent());

        $this->assertObjectHasAttribute('status', $response);
        $this->assertObjectHasAttribute('message', $response);
        $this->assertEquals(true, $response->status);
        $this->assertEquals( LanguageTranslationHelper::translate('institutions.success.accreditation_deleted'), $response->message);

        $this->notSeeInDatabase((new InstitutionAccreditation())->getTable(),[
            'id' => $accreditation_id
        ]);

        Schema::disableForeignKeyConstraints();
        DB::table((new Institution())->getTable())->truncate();
        DB::table((new InstitutionAccreditation())->getTable())->truncate();
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reset the properties to save memory after each test.
     * @see https://kriswallsmith.net/post/18029585104/faster-phpunit
     */
    protected function tearDown(): void
    {
        (function () {
            foreach ((new \ReflectionObject($this))->getProperties() as $prop) {
                if ($prop->isStatic() || strpos($prop->getDeclaringClass()->getName(), 'PHPUnit\\') === 0) {
                    continue;
                }

                unset($this->{$prop->getName()});
            }
        })->call($this);
    }
}
