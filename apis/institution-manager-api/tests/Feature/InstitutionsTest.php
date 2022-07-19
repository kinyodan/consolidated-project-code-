<?php
namespace Feature;

use App\Http\Controllers\Helpers\CraydelHelperFunctions;
use App\Http\Controllers\Helpers\LanguageTranslationHelper;
use App\Models\Institution;
use App\Models\InstitutionAccreditation;
use App\Models\InstitutionGallery;
use App\Models\InstitutionReview;
use App\Models\InstitutionUpload;
use Faker\Factory;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use TestCase;
use TestingHelperFunction;

class InstitutionsTest extends TestCase
{
    use TestingHelperFunction;

    /**
     * Test if the user access the institutions list endpoint.
     */
    public function testIfTheInstitutionsExistsRouteExists()
    {
        $this->withoutExceptionHandling();
        $this->get('institutions/admin');
        $this->assertResponseStatus(200);
    }

    /**
     * Test if user can list institutions
     */
    public function testIfUserCanListInstitutions()
    {
        $this->withoutExceptionHandling();

        $token = file_get_contents(storage_path('test-token.txt'));

        $this->get('institutions/admin', [
            'token' => $token,
            'locale' => 'en'
        ])
            ->seeJsonContains([
                'status' => true,
                'message' => LanguageTranslationHelper::translate('institutions.success.listed')
            ]);
    }

    /**
     * Test if user can create an institution
     */
    public function testIfUserCanCreateAnInstitution()
    {
        $this->withoutExceptionHandling();
        Schema::disableForeignKeyConstraints();
        DB::table((new Institution())->getTable())->truncate();
        Schema::enableForeignKeyConstraints();

        $token = file_get_contents(storage_path('test-token.txt'));
        $institution_name = Factory::create()->company;
        $accredited_by_acronym = Factory::create()->randomNumber(4);

        $this->post('institutions/admin/create', [
            'country_code' => 'KE',
            'institution_type' => 1,
            'ownership_type' => 'Private',
            'institution_name' => $institution_name,
            'accredited_by_acronym' => $accredited_by_acronym,
            'description' => Factory::create()->text,
            'profile_details' => Factory::create()->randomHtml(),
            'is_deleted' => 0,
            'is_active' => 1,
        ], [
            'token' => $token,
            'locale' => 'en'
        ])
            ->seeJsonContains([
                'status' => true,
                'message' => LanguageTranslationHelper::translate('institutions.success.created')
            ])
            ->seeInDatabase((new Institution())->getTable(), [
                'country_code' => 'KE',
                'institution_name_slug' => CraydelHelperFunctions::slugifyString($institution_name),
                'accredited_by_acronym' => $accredited_by_acronym
            ]);

        Schema::disableForeignKeyConstraints();
        DB::table((new Institution())->getTable())->truncate();
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Test if user can view the institutions details. First create the file.
     */
    public function testIfUserCanViewTheInstitutionDetails()
    {
        $this->withoutExceptionHandling();
        Schema::disableForeignKeyConstraints();
        DB::table((new Institution())->getTable())->truncate();
        Schema::enableForeignKeyConstraints();

        $token = file_get_contents(storage_path('test-token.txt'));
        $institution_name = Factory::create()->company;

        $this->post('institutions/admin/create', [
            'country_code' => 'KE',
            'institution_type' => 1,
            'ownership_type' => 'Private',
            'institution_name' => $institution_name,
            'description' => Factory::create()->text,
            'profile_details' => Factory::create()->randomHtml(),
            'is_deleted' => 0,
            'is_active' => 1,
        ], [
            'token' => $token,
            'locale' => 'en'
        ])
            ->seeJsonContains([
                'status' => true,
                'message' => LanguageTranslationHelper::translate('institutions.success.created')
            ])
            ->seeInDatabase((new Institution())->getTable(), [
                'country_code' => 'KE',
                'institution_name_slug' => CraydelHelperFunctions::slugifyString($institution_name)
            ]);

        $institution = Institution::all()->first();

        $this->get('institutions/admin/' . $institution->institution_code . '/edit', [
            'token' => $token,
            'locale' => 'en'
        ])->seeJsonContains([
            'status' => true,
            'message' => LanguageTranslationHelper::translate('institutions.success.listed'),
            'data' => $institution->toArray()
        ]);

        Schema::disableForeignKeyConstraints();
        DB::table((new Institution())->getTable())->truncate();
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Test if user can upload an institution list in excel
     */
    public function testIfUserCanUploadAnInstitutionExcel()
    {
        //clear database before tests
        $this->withoutExceptionHandling();
        Schema::disableForeignKeyConstraints();
        DB::table((new InstitutionUpload())->getTable())->truncate();
        Schema::enableForeignKeyConstraints();

        $token = file_get_contents(storage_path('test-token.txt'));

        //prepare the file
        $fileToUpload = storage_path('Institutions_for_Uploading.xlsx');
        $fileName = uniqid('institution-list-') . '.xlsx';
        $filePath = sys_get_temp_dir() . '/' . $fileName;
        copy($fileToUpload, $filePath);

        //create http file instance
        $uploadFile = new UploadedFile($filePath, $fileName, 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', null, true);

        //transform headers for api call
        $server = $this->transformHeadersToServerVars(['token' => $token, 'locale' => 'en']);


        //make api call
        $response = $this->call('POST',
            'institutions/admin/upload',
            [], [], ['institution_list' => $uploadFile], $server
        );

        //get the content and make necessary checks
        $response->assertStatus(200);
        $content = json_decode($response->getContent());
        $this->assertObjectHasAttribute('status',$content);
        $this->assertTrue($content->status);
        $this->assertEquals($content->message,LanguageTranslationHelper::translate('institutions.success.uploaded'));

        //clear the table after tests
        $this->withoutExceptionHandling();
        Schema::disableForeignKeyConstraints();
        DB::table((new InstitutionUpload())->getTable())->truncate();
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Test if can request for summary data
    */
    public function testIfCanFetchSummaryData(){
        $this->withoutExceptionHandling();
        Schema::disableForeignKeyConstraints();
        DB::table((new Institution())->getTable())->truncate();
        Schema::enableForeignKeyConstraints();

        $token = file_get_contents(storage_path('test-token.txt'));
        $institution_name = Factory::create()->company;

        $this->post('institutions/admin/create', [
            'country_code' => 'KE',
            'institution_type' => 1,
            'ownership_type' => 'Private',
            'institution_name' => $institution_name,
            'description' => Factory::create()->text,
            'profile_details' => Factory::create()->randomHtml(),
            'is_deleted' => 0,
            'is_active' => 1,
        ], [
            'token' => $token,
            'locale' => 'en'
        ])
            ->seeJsonContains([
                'status' => true,
                'message' => LanguageTranslationHelper::translate('institutions.success.created')
            ])
            ->seeInDatabase((new Institution())->getTable(), [
                'country_code' => 'KE',
                'institution_name_slug' => CraydelHelperFunctions::slugifyString($institution_name)
            ]);

        $institution_code = DB::table((new Institution())->getTable())
            ->value('institution_code');

        $response = $this->get('institutions/rpc/get-summary/'.$institution_code);
        $content = json_decode($response->response->content());
        $this->assertObjectHasAttribute('status', $content);
        $this->assertObjectHasAttribute('data', $content);
        $this->assertTrue($content->status);

        Schema::disableForeignKeyConstraints();
        DB::table((new Institution())->getTable())->truncate();
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Test is a user can rewrite a review
    */
    public function testIfUserCanReviewAnInstitution(){
        $this->withoutExceptionHandling();
        Schema::disableForeignKeyConstraints();
        DB::table((new Institution())->getTable())->truncate();
        DB::table((new InstitutionReview())->getTable())->truncate();
        Schema::enableForeignKeyConstraints();

        $token = file_get_contents(storage_path('test-token.txt'));
        $institution_name = Factory::create()->company;

        $this->post('institutions/admin/create', [
            'country_code' => 'KE',
            'institution_type' => 1,
            'ownership_type' => 'Private',
            'institution_name' => $institution_name,
            'description' => Factory::create()->text,
            'profile_details' => Factory::create()->randomHtml(),
            'is_deleted' => 0,
            'is_active' => 1,
        ], [
            'token' => $token,
            'locale' => 'en'
        ])
            ->seeJsonContains([
                'status' => true,
                'message' => LanguageTranslationHelper::translate('institutions.success.created')
            ])
            ->seeInDatabase((new Institution())->getTable(), [
                'country_code' => 'KE',
                'institution_name_slug' => CraydelHelperFunctions::slugifyString($institution_name)
            ]);

        $institution_code = DB::table((new Institution())->getTable())
            ->value('institution_code');

        $review = Factory::create()->sentence;
        $course_name = Factory::create()->company;

        $this->post('institutions/admin/'.$institution_code.'/review',[
            'rating_score' => 4.5,
            'rated_by' => 'John Kibee',
            'course_code' => $course_name,
            'graduation_year' => '1960',
            'review' => $review
        ],[
            'token' => $token,
            'locale' => 'en'
        ])
            ->seeJsonContains([
                'status' => true,
                'message' => LanguageTranslationHelper::translate('institutions.success.review_submitted')
            ])
            ->seeInDatabase((new InstitutionReview())->getTable(), [
                'institution_code' => $institution_code,
                'rating_score' => 4.5,
                'graduation_year' => '1960',
                'course_taken' => $course_name,
                'review' => $review
            ]);

        Schema::disableForeignKeyConstraints();
        DB::table((new InstitutionReview())->getTable())->truncate();
        DB::table((new Institution())->getTable())->truncate();
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Test if the user can feature an institution
    */
    public function testIfUserFeatureAInstitution(){
        $this->withoutExceptionHandling();
        Schema::disableForeignKeyConstraints();
        DB::table((new Institution())->getTable())->truncate();
        DB::table((new InstitutionReview())->getTable())->truncate();
        Schema::enableForeignKeyConstraints();

        $institution_code = Str::random(10);

        Institution::factory()->count(1)->create([
            'institution_code' => $institution_code,
            'is_featured' => 0
        ]);

        $token = file_get_contents(storage_path('test-token.txt'));

        $this->post('institutions/admin/'.$institution_code.'/feature',
            []
            ,[
                'token' => $token,
                'locale' => 'en'
            ])
            ->seeJsonContains([
                'status' => true,
                'message' => LanguageTranslationHelper::translate('institutions.success.featured')
            ]);

        Schema::disableForeignKeyConstraints();
        DB::table((new InstitutionReview())->getTable())->truncate();
        DB::table((new Institution())->getTable())->truncate();
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Test if the user can un-feature an institution
    */
    public function testIfUserUn_FeatureAnInstitution(){
        $this->withoutExceptionHandling();
        Schema::disableForeignKeyConstraints();
        DB::table((new Institution())->getTable())->truncate();
        DB::table((new InstitutionReview())->getTable())->truncate();
        Schema::enableForeignKeyConstraints();

        $institution_code = Str::random(10);

        Institution::factory()->count(1)->create([
            'institution_code' => $institution_code,
            'is_featured' => 1
        ]);

        $token = file_get_contents(storage_path('test-token.txt'));

        $this->post('institutions/admin/'.$institution_code.'/feature',
            []
            ,[
                'token' => $token,
                'locale' => 'en'
            ])
            ->seeJsonContains([
                'status' => true,
                'message' => LanguageTranslationHelper::translate('institutions.success.un_featured')
            ]);

        Schema::disableForeignKeyConstraints();
        DB::table((new InstitutionReview())->getTable())->truncate();
        DB::table((new Institution())->getTable())->truncate();
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Test if use can view the institution details.
    */
    public function testIfUserCanViewASingleInstitutionDetails(){
        $this->withoutExceptionHandling();
        Schema::disableForeignKeyConstraints();
        DB::table((new Institution())->getTable())->truncate();
        DB::table((new InstitutionReview())->getTable())->truncate();
        DB::table((new InstitutionGallery())->getTable())->truncate();
        Schema::enableForeignKeyConstraints();

        $institution_code = Str::random(10);

        Institution::factory()->count(1)->create([
            'institution_code' => $institution_code,
            'is_featured' => 1
        ]);

        InstitutionGallery::factory()->count(2)->create([
            'institution_code' => $institution_code,
            'is_featured' => 1
        ]);

        InstitutionGallery::factory()->count(3)->create([
            'type' => 'Image',
            'institution_code' => $institution_code,
            'small_image_url' => Factory::create()->imageUrl(),
            'medium_image_url' => Factory::create()->imageUrl(),
            'big_image_url' => Factory::create()->imageUrl(),
            'is_featured' => 1,
            'is_deleted' => 0
        ]);

        InstitutionGallery::factory()->count(2)->create([
            'institution_code' => $institution_code,
            'is_featured' => 1,
            'is_deleted' => 1
        ]);

        InstitutionGallery::factory()->count(2)->create([
            'type' => 'Image',
            'institution_code' => $institution_code,
            'is_featured' => 1,
            'is_deleted' => 0,
            'video_url' => null,
            'big_image_url' => null
        ]);

        InstitutionAccreditation::factory()->count(4)->create([
            'institution_code' => $institution_code
        ]);

        $this->get('institutions/'.$institution_code)
            ->seeJsonContains([
                'status' => true,
                'message' => LanguageTranslationHelper::translate('institutions.success.details_shown')
            ])->seeJsonStructure([
                'data' => [
                    'institution_code',
                    'ownership_type',
                    'institution_name',
                    'description',
                    'primary_image',
                    'city',
                    'type' => [
                        'id',
                        'name'
                    ],
                    'country' => [
                        'continent',
                        'geographical_region',
                        'iso_code',
                        'iso3_code',
                        'timezone'
                    ],
                    'reviews',
                    'gallery',
                    'accreditations'
                ]
            ]);

        Schema::disableForeignKeyConstraints();
        DB::table((new InstitutionReview())->getTable())->truncate();
        DB::table((new InstitutionGallery())->getTable())->truncate();
        DB::table((new Institution())->getTable())->truncate();
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Test institution RPC
    */
    public function testTheInstitutionRPCResponse(){
        $this->withoutExceptionHandling();
        Schema::disableForeignKeyConstraints();
        DB::table((new Institution())->getTable())->truncate();
        Schema::enableForeignKeyConstraints();

        Institution::factory()->create([
            'country_code' => 'KE',
            'institution_code' => 'x9qkVi0ZHA'
        ]);

        $this->get('institutions/rpc/institutions-list')
            ->seeJsonContains([
                'status' => true,
                'message' => 'List'
            ])->seeJsonStructure([
                'data' => [
                    '*' => [
                        'institution_code',
                        'ownership_type',
                        'institution_name',
                        'description',
                        'primary_image',
                        'city',
                        'type' => [
                            'id',
                            'name'
                        ],
                        'country' => [
                            'continent',
                            'geographical_region',
                            'iso_code',
                            'iso3_code',
                            'timezone'
                        ]
                    ]
                ]
            ]);
    }

    /**
     * Test if can retrieve a list of active institution names
    */
    public function testIfCanRetrieveAListOfActiveInstitutionNames(){
        $this->withoutExceptionHandling();
        Schema::disableForeignKeyConstraints();
        DB::table((new Institution())->getTable())->truncate();
        Schema::enableForeignKeyConstraints();

        Institution::factory()->create([
            'country_code' => 'KE',
            'institution_code' => 'x9qkVi0ZHA'
        ]);

        $this->get('institutions/rpc/institution-names')
            ->seeJsonContains([
                'status' => true,
                'message' => 'List'
            ])->seeJsonStructure([
                'data' => [
                    '*' => [
                        'institution_name'
                    ]
                ]
            ]);
    }

    /**
     * Test if you can get institution details by name
    */
    public function testIfCanGetInstitutionDetailsByName(){
        $this->withoutExceptionHandling();
        Schema::disableForeignKeyConstraints();
        DB::table((new Institution())->getTable())->truncate();
        Schema::enableForeignKeyConstraints();

        $institution_name = Factory::create()->company;

        $institution = Institution::factory()->create([
            'country_code' => 'KE',
            'institution_code' => 'x9qkVi0ZHA',
            'institution_name' => $institution_name,
            'institution_name_slug' => CraydelHelperFunctions::slugifyString($institution_name)
        ]);

        $this->get('institutions/rpc/get-institution-details-by-name/'.$institution->first()->institution_name)
            ->seeJsonContains([
                'status' => true,
                'message' => 'Details'
            ])->seeJsonStructure([
                'data' => [
                    'country_code',
                    'institution_code',
                    'institution_type_name',
                    'institution_name',
                    'ownership_type',
                    'city',
                    'accredited_by',
                    'institution_code',
                    'country' => [
                        'continent',
                        'iso_code',
                        'geographical_region'
                    ]
                ]
            ]);
    }

    /**
     * Test if user can get the list of countries with active programs
    */
    public function testIfUserCanGetTheListOfCountriesWithActivePrograms(){
        $this->withoutExceptionHandling();

        $this->get('institutions/get-countries-with-active-programs')
            ->seeJsonContains([
                'status' => true,
                'message' => 'Listed'
            ])->seeJsonStructure([
                'data' => [
                    '*' => [
                        'country_name',
                        'country_code',
                    ]
                ]
            ]);
    }

    /**
     * Test if user can get the list of all intakes in a country
     */
    public function testIfUserCanGetTheListOfAllIntakesInACountry(){
        $this->withoutExceptionHandling();

        $this->get('institutions/get-countries-intakes/ke')
            ->seeJsonContains([
                'status' => true,
                'message' => 'Listed'
            ])->seeJsonStructure([
                'data' => [
                    '*' => [
                        'intake'
                    ]
                ]
            ]);
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
