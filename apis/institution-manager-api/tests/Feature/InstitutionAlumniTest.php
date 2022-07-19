<?php
namespace Feature;

use App\Events\InstitutionUpdatedEvent;
use App\Http\Controllers\Administration\Commands\Alumni\UpdateAlumnusProfileCommandController;
use App\Http\Controllers\Administration\InstitutionController;
use App\Http\Controllers\Helpers\CraydelHelperFunctions;
use App\Http\Controllers\Helpers\JWTHelper;
use App\Http\Controllers\Helpers\LanguageTranslationHelper;
use App\Http\Controllers\Traits\CanLog;
use App\Jobs\DeleteImagesFromTheCDN;
use App\Jobs\UploadInstitutionAlumnusImageToCDNJob;
use App\Models\AcademicDiscipline;
use App\Models\Institution;
use App\Models\InstitutionAlumnus;
use App\Models\JobTitle;
use App\Models\Questions;
use App\Models\QuestionsResponse;
use App\Models\Reviews;
use Carbon\Carbon;
use Faker\Factory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Schema;
use PhpOffice\PhpSpreadsheet\Calculation\MathTrig\Random;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use TestCase;
use TestingHelperFunction;

class InstitutionAlumniTest extends TestCase
{
    use TestingHelperFunction, CanLog;

    /**
     * @var
    */
    protected $institution, $token, $user;

    /**
     * Set up the test
    */
    public function setUp(): void
    {
        parent::setUp();

        $this->withoutExceptionHandling();
        $this->clearDB();
        $this->artisan('db:seed');

        $institutions = Institution::factory()->count(1)->create([
            'institution_code' => $this->default_institution_code,
            'is_featured' => 1
        ]);

        $this->institution = $institutions->first();
        $this->token = trim(file_get_contents(storage_path('test-token.txt')));
        $this->user = JWTHelper::decode($this->token);
    }

    /**
     * Test is the user can build a new alumnus request
    */
    public function testIfTheAdminUserCanBuildANewAlumnusRequest(){
        $this->get('institutions/admin/'.$this->institution->institution_code.'/alumni/build', [
            'token' => $this->token,
            'locale' => 'en'
        ])->seeJsonContains([
            'status' => true,
            'message' => LanguageTranslationHelper::translate('institutions.success.alumnus_built')
        ])->seeJsonStructure([
            'data' => [
                'job_titles' => [
                    '*' => [
                        'job_title',
                        'description',
                        'is_active'
                    ]
                ]
            ]
        ]);
    }

    /**
     * Test is user can list the alumni
     */
    public function testIfTheAdminUserCanListAlumni(){
        InstitutionAlumnus::factory()->count(10)->create([
            'institution_code' => $this->institution->institution_code
        ]);

        $this->get('institutions/admin/'.$this->institution->institution_code.'/alumni', [
            'token' => $this->token,
            'locale' => 'en'
        ])->seeJsonContains([
                'status' => true,
                'message' => LanguageTranslationHelper::translate('institutions.success.alumni_listed')
        ])->seeJsonStructure([
            'data' => [
                'items' => [
                    '*' => [
                        'id',
                        'alumni_name',
                        'graduation_year',
                        'course_taken',
                        'current_employer',
                        'current_position',
                        'personal_profile_url',
                        'big_alumnus_image_path',
                        'small_alumnus_image_path'
                    ]
                ],
                'items_per_page',
                'current_page',
                'previous_page',
                'next_page',
                'number_of_pages',
                'items_count'
            ]
        ]);
    }

    /**
     * Test is user can add an alumnus
    */
    public function testIfTheAdminUserCanAddAnAlumnus(){
        $file = Factory::create()->image(storage_path('app'.DIRECTORY_SEPARATOR.'staged-images'), 1500, 1000);
        $uploadFile = new UploadedFile(
            $file,
            basename($file),
            'image/png',
            null,
            true
        );

        $server = $this->transformHeadersToServerVars([
            'token' => $this->token,
            'locale' => 'en'
        ]);

        $alumni_name = Factory::create()->name;
        $graduation_year = Factory::create()->year;
        $course_taken = Factory::create()->company;
        $current_employer = Factory::create()->company;
        $current_position = JobTitle::all()->random(1)->first()->id;
        $personal_profile_url = Factory::create()->url;

        $payload = [
            'alumni_name' => $alumni_name,
            'graduation_year' => $graduation_year,
            'course_taken' => $course_taken,
            'current_employer' => $current_employer,
            'current_position' => $current_position,
            'personal_profile_url' => $personal_profile_url
        ];

        Queue::fake();

        $response = $this->call('POST',
            'institutions/admin/'.$this->institution->institution_code.'/alumni/add',
            $payload,[
                'token' => $this->token,
                'locale' => 'en'
            ],[
                'alumnus_image' => $uploadFile
            ], $server);

        Queue::assertPushed(UploadInstitutionAlumnusImageToCDNJob::class);

        $this->assertJson($response->getContent());
        $this->seeJsonContains([
            'status' => true,
            'message' => LanguageTranslationHelper::translate('institutions.success.alumnus_saved')
        ]);

        $this->seeInDatabase((new InstitutionAlumnus())->getTable(),[
            'institution_code' => $this->institution->institution_code,
            'alumni_name' => $alumni_name,
            'alumni_name_slug' => CraydelHelperFunctions::slugifyString($alumni_name),
            'graduation_year' => $graduation_year,
            'course_taken' => $course_taken,
            'current_employer' => $current_employer,
            'current_position' => $current_position,
            'personal_profile_url' => $personal_profile_url,
            'is_active' => 1,
            'is_deleted' => 0,
            'created_by' => $this->user->email,
            'updated_by' => $this->user->email
        ]);

        Schema::disableForeignKeyConstraints();
        DB::table((new InstitutionAlumnus())->getTable())->truncate();
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Test is user can get the alumnus details
    */
    public function testIfTheAdminCanGetTheAlumnusDetails(){
        $alumni = InstitutionAlumnus::factory()->count(1)->create([
            'institution_code' => $this->institution->institution_code
        ]);

        $alumnus = $alumni->first();

        $this->get('institutions/admin/'.$this->institution->institution_code.'/alumni/'.$alumnus->id, [
            'token' => $this->token,
            'locale' => 'en'
        ])->seeJsonContains([
            'status' => true,
            'message' => LanguageTranslationHelper::translate('institutions.success.alumni_shown')
        ])->seeJsonStructure([
            'data' => [
                'alumnus' => [
                    'id',
                    'alumni_name',
                    'graduation_year',
                    'course_taken',
                    'current_employer',
                    'current_position',
                    'personal_profile_url',
                    'big_alumnus_image_path',
                    'small_alumnus_image_path',
                    'current_position_name'
                ]
            ]
        ]);
    }

    /**
     * Test is user can add an alumnus
    */
    public function testIfTheAdminUserCanUpdateTheAlumnusDetails(){
        $file = Factory::create()->image(storage_path('app'.DIRECTORY_SEPARATOR.'staged-images'), 1500, 1000);
        $uploadFile = new UploadedFile(
            $file,
            basename($file),
            'image/png',
            null,
            true
        );

        $server = $this->transformHeadersToServerVars([
            'token' => $this->token,
            'locale' => 'en'
        ]);

        $alumni = InstitutionAlumnus::factory()->count(1)->create([
            'institution_code' => $this->institution->institution_code
        ]);

        $alumnus = $alumni->first();

        $alumni_name = Factory::create()->name;
        $graduation_year = Factory::create()->year;
        $course_taken = Factory::create()->company;
        $current_employer = Factory::create()->company;
        $current_position = JobTitle::all()->random(1)->first()->id;
        $personal_profile_url = Factory::create()->url;

        $payload = [
            'alumni_name' => $alumni_name,
            'graduation_year' => $graduation_year,
            'course_taken' => $course_taken,
            'current_employer' => $current_employer,
            'current_position' => $current_position,
            'personal_profile_url' => $personal_profile_url
        ];

        Queue::fake();

        $response = $this->call('POST',
            'institutions/admin/'.$this->institution->institution_code.'/alumni/'.$alumnus->id.'/update',
            $payload,[
                'token' => $this->token,
                'locale' => 'en'
            ],[
                'alumnus_image' => $uploadFile
            ], $server);

        Queue::assertPushed(DeleteImagesFromTheCDN::class);
        Queue::assertPushed(UploadInstitutionAlumnusImageToCDNJob::class, function ($job) use($alumnus){
            return $job->alumnus_id == $alumnus->id;
        });

        $this->assertJson($response->getContent());
        $this->seeJsonContains([
            'status' => true,
            'message' => LanguageTranslationHelper::translate('institutions.success.alumnus_update')
        ]);

        $this->seeInDatabase((new InstitutionAlumnus())->getTable(),[
            'id' => $alumnus->id,
            'institution_code' => $this->institution->institution_code,
            'alumni_name' => $alumni_name,
            'alumni_name_slug' => CraydelHelperFunctions::slugifyString($alumni_name),
            'graduation_year' => $graduation_year,
            'course_taken' => $course_taken,
            'current_employer' => $current_employer,
            'current_position' => $current_position,
            'personal_profile_url' => $personal_profile_url,
            'is_active' => 1,
            'is_deleted' => 0,
            'updated_by' => $this->user->email
        ]);
    }

    /**
     * Test is user can add an alumnus
    */
    public function testIfTheAdminUserCanDeleteAnAlumnus(){
        $alumni = InstitutionAlumnus::factory()->count(1)->create([
            'institution_code' => $this->institution->institution_code
        ]);

        Event::fake();
        Queue::fake();

        $this->post('institutions/admin/'.$this->institution->institution_code.'/alumni/'.$alumni->first()->id.'/delete', [],[
            'token' => $this->token,
            'locale' => 'en'
        ])->seeJsonContains([
            'status' => true,
            'message' => LanguageTranslationHelper::translate('institutions.success.alumnus_deleted')
        ])->notSeeInDatabase((new InstitutionAlumnus())->getTable(),[
            'id' => $alumni->first()->id
        ]);

        Event::assertDispatched(InstitutionUpdatedEvent::class, function ($event){
            return $event->institution_code = $this->institution->institution_code;
        });

        Queue::assertPushed(DeleteImagesFromTheCDN::class);
    }

    /**
     * Test user can get their alumnus details
    */
    public function testIfUserCanGetTheirAlumnusDetails(){
        $alumni = InstitutionAlumnus::factory()->count(1)->create([
            'institution_code' => $this->institution->institution_code
        ]);

        $alumnus = $alumni->first();

        $this->post('/institutions/rpc/get-alumni-details-slug', [
            'slug' => $alumnus->alumni_name_slug
        ],[
            'token' => $this->token,
            'locale' => 'en'
        ])->seeJsonContains([
            'status' => true,
            'message' => LanguageTranslationHelper::translate('institutions.success.alumni_shown')
        ])->seeJsonStructure([
            'data' => [
                'id',
                'institution_code',
                'alumni_name',
                'graduation_year',
                'course_taken',
                'current_employer',
                'current_position',
                'current_location',
                'email',
                'university_name',
                'personal_profile_url',
                'big_alumnus_image_path',
                'small_alumnus_image_path',
                'unique_url',
                'is_finished',
                'status',
                'question_step',
                'current_position_name',
                'course_category'
            ]
        ]);
    }

    /**
     * Test user can get their alumnus details with a review
    */
    public function testIfUserCanGetTheirAlumnusDetailsWithReview(){
        $alumni = InstitutionAlumnus::factory()->count(1)->create([
            'institution_code' => $this->institution->institution_code
        ]);

        Reviews::factory()->count(1)->create([
            'institution_code' => $this->institution->institution_code
        ]);

        $alumnus = $alumni->first();

        $this->post('/institutions/rpc/get-alumni-details-slug', [
            'slug' => $alumnus->alumni_name_slug
        ],[
            'token' => $this->token,
            'locale' => 'en'
        ])->seeJsonContains([
            'status' => true,
            'message' => LanguageTranslationHelper::translate('institutions.success.alumni_shown')
        ])->seeJsonStructure([
            'data' => [
                'id',
                'institution_code',
                'alumni_name',
                'graduation_year',
                'course_taken',
                'current_employer',
                'current_position',
                'current_location',
                'email',
                'university_name',
                'personal_profile_url',
                'big_alumnus_image_path',
                'small_alumnus_image_path',
                'unique_url',
                'is_finished',
                'status',
                'question_step',
                'current_position_name',
                'course_category',
                'review' => [
                    'id',
                    'reviews',
                    'customer_consented_to_review_publish',
                    'customer_consented_to_use_linkedin_photo',
                    'up_vote',
                    'down_vote',
                    'is_published',
                    'created_at'
                ]
            ]
        ]);
    }

    /**
     * Test if user get can their questions list
    */
    public function testIfUserCanGetTheirQuestionsList(){
        $alumni = InstitutionAlumnus::factory()->count(1)->create([
            'institution_code' => $this->institution->institution_code
        ]);

        $alumnus = $alumni->first();

        $this->get('/institutions/rpc/get-alumni-questions/'.$alumnus->id,[
            'token' => $this->token,
            'locale' => 'en'
        ])->seeJsonContains([
            'status' => true,
            'message' => LanguageTranslationHelper::translate('institutions.success.alumni_questions_listed')
        ])->seeJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'title',
                    'is_published',
                    'questions' => [
                        '*' => [
                            'id',
                            'question_category_id',
                            'title',
                            'description',
                            'is_published',
                            'current_score'
                        ]
                    ]
                ],
            ]
        ]);
    }

    /**
     * Test if the alumnus can submit survey responses
    */
    public function testIfTheAlumnusCanSubmitSurveyResponse(){
        $alumni = InstitutionAlumnus::factory()->count(1)->create([
            'institution_code' => $this->institution->institution_code
        ]);

        $alumnus = $alumni->first();
        $question = Questions::all()->random(3)->first();
        $scores = Random::randBetween(1, 5);

        $this->post('/institutions/rpc/alumnus-submit-question-response', [
            'institution_alumni_id' => $alumnus->id,
            'question_id' => $question->id,
            'question_category_id' => $question->question_category_id,
            'institution_code' => $this->institution->institution_code,
            'scores' => $scores,
            'question_step' => 'StepOne'
        ],[
            'token' => $this->token,
            'locale' => 'en'
        ])->seeJsonContains([
            'status' => true,
            'message' => 'Question response submitted'
        ])->seeJsonStructure([
            'data' => [
                'answered_questions_list' => [
                    '*' => [
                        '*' => [
                            'question_id',
                            'question_category_id'
                        ]
                    ]
                ],
                'answered_questions_count',
                'latest_question_section',
                'total_questions'
            ]
        ])->seeInDatabase((new QuestionsResponse())->getTable(), [
            'institution_alumni_id' => $alumnus->id,
            'question_category_id' => $question->question_category_id,
            'institution_code' => $this->institution->institution_code,
            'question_id' => $question->id,
            'scores' => $scores
        ])->seeInDatabase((new InstitutionAlumnus())->getTable(),[
            'id' => $alumnus->id,
            'question_step' => 'StepOne'
        ]);
    }

    /**
     * Test if teh alumnus can submit a review
    */
    public function testIfTheAlumnusCanSubmitAReview(){
        $alumni = InstitutionAlumnus::factory()->count(1)->create([
            'institution_code' => $this->institution->institution_code
        ]);

        $alumnus = $alumni->first();
        $reviews = Factory::create()->paragraph;
        $is_consented = 1;
        $show_your_profile = 1;

        $this->post('/institutions/rpc/alumni-submit-review', [
            'institution_alumni_id' => $alumnus->id,
            'institution_code' => $this->institution->institution_code,
            'reviews' => $reviews,
            'is_consented' => $is_consented,
            'show_your_profile' => $show_your_profile
        ],[
            'token' => $this->token,
            'locale' => 'en'
        ])->seeJsonContains([
            'status' => true,
            'message' => LanguageTranslationHelper::translate('alumni.success.reviews')
        ])->seeInDatabase((new Reviews())->getTable(), [
            'institution_alumni_id' => $alumnus->id,
            'institution_code' => $this->institution->institution_code,
            'reviews' => $reviews,
        ])->seeInDatabase((new InstitutionAlumnus())->getTable(),[
            'id' => $alumnus->id,
            'is_consented' => $is_consented,
            'show_your_profile' => $show_your_profile
        ]);
    }

    /**
     * Test is the alumnus can update their profile
    */
    public function testIfTheAlumnusCanUpdateTheirProfile(){
        $alumni = InstitutionAlumnus::factory()->count(1)->create([
            'institution_code' => $this->institution->institution_code
        ]);

        $alumnus = $alumni->first();
        $alumni_name = Factory::create()->name;
        $graduation_year = Carbon::now()->subYears(Random::randBetween(1, 20))->year;
        $email = Factory::create()->email;
        $course_type = Factory::create()->randomElement((new UpdateAlumnusProfileCommandController(new InstitutionController()))->allowed_course_types);
        $course_category = AcademicDiscipline::all()->random(10)->first()->discipline_code;
        $course_taken = Factory::create()->company;
        $current_employer = Factory::create()->company;
        $current_position = Factory::create()->jobTitle;
        $current_location = Factory::create()->city;

        $this->post('/institutions/rpc/alumni-update-profile/'.$alumnus->alumni_name_slug, [
            'institution_alumni_id' => $alumnus->id,
            'institution_code' => $this->institution->institution_code,
            'alumni_name' => $alumni_name,
            'graduation_year' => $graduation_year,
            'course_type' => $course_type,
            'course_category' => $course_category,
            'course_taken' => $course_taken,
            'current_employer' => $current_employer,
            'current_position' => $current_position,
            'current_location' => $current_location,
            'email' => $email
        ],[
            'token' => $this->token,
            'locale' => 'en'
        ])->seeJsonContains([
            'status' => true,
            'message' => LanguageTranslationHelper::translate('alumni.success.profile')
        ])->seeInDatabase((new InstitutionAlumnus())->getTable(), [
            'institution_code' => $this->institution->institution_code,
            'alumni_name' => $alumni_name,
            'graduation_year' => $graduation_year,
            'course_type' => $course_type,
            'course_category' => $course_category,
            'course_taken' => $course_taken,
            'current_employer' => $current_employer,
            'current_position' => $current_position,
            'current_location' => $current_location,
            'email' => $email,
            'university_name' => $this->institution->institution_name,
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

    /**
     * Test if user get list of institutions
     */
    public function testIfUserCanListInstitutions()
    {
        $this->withoutExceptionHandling();

        $this->get('institutions/rpc/institution-names', [
            'token' => $this->token,
            'locale' => 'en'
        ])->seeJsonContains([
                'status' => true,
                'message' => "List"
        ])->seeJsonStructure([
            'data' => [
                '*' => [
                    'institution_name',
                    'institution_code',
                ]
            ]
        ]);

    }
}
