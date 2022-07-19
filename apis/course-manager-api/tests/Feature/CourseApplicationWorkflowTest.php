<?php
namespace Feature;

use App\Events\CourseApplicationLeadSubmittedToCRMEvent;
use App\Http\Controllers\Courses\Commands\UpdateCourseStaticsCommandController;
use App\Http\Controllers\CraydelTypes\LeadType;
use App\Http\Controllers\CraydelTypes\LeadTypeCollection;
use App\Http\Controllers\Helpers\CraydelURLHelper;
use App\Http\Controllers\Helpers\LanguageTranslationHelper;
use App\Http\Controllers\Helpers\LearningModeHelper;
use App\Http\Controllers\Helpers\ParsePhoneNumberHelper;
use App\Http\Controllers\Providers\LeadManagement\ILeadProvider;
use App\Http\Controllers\Providers\LeadManagement\LeadManagementController;
use App\Jobs\PushLeadAndOpportunitiesDataToDateLakeJob;
use App\Jobs\UpdateLeadFirstContactDate;
use App\Models\Course;
use App\Models\Leads;
use Carbon\Carbon;
use Exception;
use Faker\Factory;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Schema;
use TestCase;
use TestingHelperFunction;

class CourseApplicationWorkflowTest extends TestCase
{
    use TestingHelperFunction;

    /**
     * @var Course $course
     */
    private Course $course;

    /**
     * @var string $course_code
     */
    protected string $course_code;

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

        foreach ($tables as $table) {
            if ($table == 'migrations') {
                continue;
            }

            $table_name = $table->{"Tables_in_{$db_name}"};
            DB::table($table_name)->truncate();
        }

        Schema::enableForeignKeyConstraints();
        $this->artisan('db:seed');

        $this->course_code = 'CO2CM6B2SBP';
        $institution_code = 'INTANHXXFAYVR';

        $this->course = Course::factory()->count(1)->create([
            'course_code' => $this->course_code,
            'institution_code' => $institution_code
        ])->first();
    }

    /**
     * Test if user can submit a lead
     */
    public function testIfUserCanSubmitLead()
    {
        $student_academic_level = Factory::create()->company;
        $how_to_fund_education = Factory::create()->text;
        $partner_agent = Factory::create()->company;
        $page_url = 'https://campaigns.craydel.com/online-masters-campaign/?dxid=Refresh_your_Career_Ad_2_0&utm_source=Facebook_Belva&utm_medium=Banner_Belva&utm_campaign=MastersOnline_WordPress_Website_Traffic&utm_id=MastersOnline_Belva#lead-form';
        $referrer_url = 'https://www.facebook.com/';
        $study_destination = 'UK';

        $payload = [
            'course_code' => $this->course_code,
            'city' => 'Nairobi',
            'country' => 'Kenya',
            'study_destination' => $study_destination,
            'course_name' => 'Test Course',
            'email' => 'ngurujohn@gmail.com',
            'full_names' => 'John Nguru',
            'mobile_number' => '0711689928',
            'mobile_number_country_code' => 'KE',
            'institution_name' => 'Makerere',
            'year_of_birth' => '1970',
            'page_url' => $page_url,
            'referrer_url' => $referrer_url,
            'education_level' => $student_academic_level,
            'education_fund' => $how_to_fund_education,
            'partner_agent' => $partner_agent
        ];

        $course = Course::all()->where('course_code', $this->course_code)->first();
        $processed_page_url = CraydelURLHelper::process($page_url);

        $this->post('courses/lead/submit',
            $payload
            , [
                'locale' => 'en'
            ])
            ->seeJsonContains([
                'status' => true,
                'message' => LanguageTranslationHelper::translate('courses.success.lead_submitted')
            ])
            ->seeJsonStructure([
                'data' => [
                    'lead_id'
                ]
            ])
            ->seeInDatabase(
                (new Leads())->getTable(), [
                    'course_code' => $this->course_code,
                    'city' => 'Nairobi',
                    'study_destination' => $study_destination,
                    'email' => 'ngurujohn@gmail.com',
                    'institution_name' => 'Makerere',
                    'year_of_birth' => '1970',
                    'page_url' => $page_url,
                    'referrer_url' => $referrer_url,
                    'utm_source' => !is_null($processed_page_url->getUtmSource()) ? $processed_page_url->getUtmSource() : '',
                    'utm_campaign' => !is_null($processed_page_url->getUtmCampaign()) ? $processed_page_url->getUtmCampaign() : '',
                    'utm_medium' => !is_null($processed_page_url->getUtmMedium()) ? $processed_page_url->getUtmMedium() : '',
                    'utm_term' => !is_null($processed_page_url->getUtmTerm()) ? $processed_page_url->getUtmTerm() : '',
                    'asset_id' => !is_null($processed_page_url->getAdSetId()) ? $processed_page_url->getAdSetId() : '',
                    'course_learning_mode' => LearningModeHelper::getLearningModeByID($course->learning_mode),
                    'current_course_intake' => $course->current_intake,
                    'student_academic_level' => $student_academic_level,
                    'how_to_fund_education' => $how_to_fund_education,
                    'partner_agent' => $partner_agent
                ]
            );
    }

    /**
     * Test if user can submit a lead with parent information
    */
    public function testIfUserCanSubmitLeadIncludingParentInformation()
    {
        $student_academic_level = Factory::create()->company;
        $how_to_fund_education = Factory::create()->text;
        $partner_agent = Factory::create()->company;
        $page_url = 'https://campaigns.craydel.com/online-masters-campaign/?dxid=Refresh_your_Career_Ad_2_0&utm_source=Facebook_Belva&utm_medium=Banner_Belva&utm_campaign=MastersOnline_WordPress_Website_Traffic&utm_id=MastersOnline_Belva#lead-form';
        $referrer_url = 'https://www.facebook.com/';
        $study_destination = 'UK';
        $parent_full_names = Factory::create()->name;
        $parent_mobile_number = '0711689955';

        $payload = [
            'course_code' => $this->course_code,
            'city' => 'Nairobi',
            'country' => 'Kenya',
            'study_destination' => $study_destination,
            'course_name' => 'Test Course',
            'email' => 'ngurujohn@gmail.com',
            'full_names' => 'John Nguru',
            'mobile_number' => '0711689928',
            'mobile_number_country_code' => 'KE',
            'institution_name' => 'Makerere',
            'year_of_birth' => '1970',
            'page_url' => $page_url,
            'referrer_url' => $referrer_url,
            'education_level' => $student_academic_level,
            'education_fund' => $how_to_fund_education,
            'partner_agent' => $partner_agent,
            'parent_full_names' => $parent_full_names,
            'parent_mobile_number' => $parent_mobile_number
        ];

        $course = Course::all()->where('course_code', $this->course_code)->first();
        $processed_page_url = CraydelURLHelper::process($page_url);

        $this->post('courses/lead/submit',
            $payload
            , [
                'locale' => 'en'
            ])
            ->seeJsonContains([
                'status' => true,
                'message' => LanguageTranslationHelper::translate('courses.success.lead_submitted')
            ])
            ->seeInDatabase(
                (new Leads())->getTable(), [
                    'course_code' => $this->course_code,
                    'city' => 'Nairobi',
                    'study_destination' => $study_destination,
                    'email' => 'ngurujohn@gmail.com',
                    'institution_name' => 'Makerere',
                    'year_of_birth' => '1970',
                    'page_url' => $page_url,
                    'referrer_url' => $referrer_url,
                    'utm_source' => !is_null($processed_page_url->getUtmSource()) ? $processed_page_url->getUtmSource() : '',
                    'utm_campaign' => !is_null($processed_page_url->getUtmCampaign()) ? $processed_page_url->getUtmCampaign() : '',
                    'utm_medium' => !is_null($processed_page_url->getUtmMedium()) ? $processed_page_url->getUtmMedium() : '',
                    'utm_term' => !is_null($processed_page_url->getUtmTerm()) ? $processed_page_url->getUtmTerm() : '',
                    'asset_id' => !is_null($processed_page_url->getAdSetId()) ? $processed_page_url->getAdSetId() : '',
                    'course_learning_mode' => LearningModeHelper::getLearningModeByID($course->learning_mode),
                    'current_course_intake' => $course->current_intake,
                    'student_academic_level' => $student_academic_level,
                    'how_to_fund_education' => $how_to_fund_education,
                    'partner_agent' => $partner_agent,
                    'parent_full_names' => $parent_full_names,
                    'parent_mobile_number' => ParsePhoneNumberHelper::makeNationalizedMobileNumber(
                        'KE',
                        $parent_mobile_number
                    )
                ]
            );
    }

    /**
     * Test if user can submit a lead with default utm data
     */
    public function testIfUserCanSubmitLeadWithDefaultUTMData()
    {
        $student_academic_level = Factory::create()->company;
        $how_to_fund_education = Factory::create()->text;
        $partner_agent = Factory::create()->company;
        $page_url = 'https://campaigns.craydel.com/online-masters-campaign';
        $referrer_url = 'https://www.facebook.com/';

        $payload = [
            'course_code' => $this->course_code,
            'city' => 'Nairobi',
            'country' => 'Kenya',
            'course_name' => 'Test Course',
            'email' => 'ngurujohn@gmail.com',
            'full_names' => 'John Nguru',
            'mobile_number' => '0711689928',
            'mobile_number_country_code' => 'KE',
            'institution_name' => 'Makerere',
            'year_of_birth' => '1970',
            'page_url' => $page_url,
            'referrer_url' => $referrer_url,
            'education_level' => $student_academic_level,
            'education_fund' => $how_to_fund_education,
            'partner_agent' => $partner_agent,
            'utm_source' => 'Test_LOCAL_STORAGE_UTM_NOT_IN_URL',
            'utm_medium' => 'Test_LOCAL_STORAGE_UTM_NOT_IN_URL',
            'utm_campaign' => 'Test_LOCAL_STORAGE_UTM_NOT_IN_URL',
            'utm_id' => 'Test_LOCAL_STORAGE_UTM_NOT_IN_URL',
            'ad_id' => 'AD_ID',
            'ad_set_id' => 'AD_SET_ID',
            'campaign_id' => 'CAMPAIGN_ID',
            'ad_name' => 'AD_NAME',
            'adset_name' => 'ADSET_NAME',
            'placement' => 'PLACEMENT',
            'site_source_name' => 'SITE_SOURCE_NAME',
            'utm_content' => 'UTM_CONTENT',
            'utm_term' => 'UTM_TERM'
        ];

        $course = Course::all()->where('course_code', $this->course_code)->first();

        $this->post('courses/lead/submit',
            $payload
            , [
                'locale' => 'en'
            ])
            ->seeJsonContains([
                'status' => true,
                'message' => LanguageTranslationHelper::translate('courses.success.lead_submitted')
            ])
            ->seeInDatabase(
                (new Leads())->getTable(), [
                    'course_code' => $this->course_code,
                    'city' => 'Nairobi',
                    'email' => 'ngurujohn@gmail.com',
                    'institution_name' => 'Makerere',
                    'year_of_birth' => '1970',
                    'page_url' => $page_url,
                    'referrer_url' => $referrer_url,
                    'utm_source' => 'Test_LOCAL_STORAGE_UTM_NOT_IN_URL',
                    'utm_campaign' => 'Test_LOCAL_STORAGE_UTM_NOT_IN_URL',
                    'utm_medium' => 'Test_LOCAL_STORAGE_UTM_NOT_IN_URL',
                    'asset_id' => 'AD_ID',
                    'course_learning_mode' => LearningModeHelper::getLearningModeByID($course->learning_mode),
                    'current_course_intake' => $course->current_intake,
                    'student_academic_level' => $student_academic_level,
                    'how_to_fund_education' => $how_to_fund_education,
                    'partner_agent' => $partner_agent,
                    'ad_id' => 'AD_ID',
                    'ad_set_id' => 'AD_SET_ID',
                    'campaign_id' => 'CAMPAIGN_ID',
                    'ad_name' => 'AD_NAME',
                    'ad_set_name' => 'ADSET_NAME',
                    'ad_placement_position' => 'PLACEMENT',
                    'site_source_name' => 'SITE_SOURCE_NAME',
                    'utm_content' => 'UTM_CONTENT',
                    'utm_term' => 'UTM_TERM'
                ]
            );
    }

    /**
     * Test if a default lead source is created if UTM information is missing.
     */
    public function testIfDefaultLeadSourceAndCampaignNameIsCreatedIfUTMInformationIsMissing()
    {
        $student_academic_level = Factory::create()->company;
        $how_to_fund_education = Factory::create()->text;
        $partner_agent = Factory::create()->company;
        $page_url = 'https://craydel.com/study-destinations/study-in-canada';

        $payload = [
            'course_code' => $this->course_code,
            'city' => 'Nairobi',
            'country' => 'Kenya',
            'course_name' => 'Test Course',
            'email' => 'ngurujohn@gmail.com',
            'full_names' => 'John Nguru',
            'mobile_number' => '0711689928',
            'mobile_number_country_code' => 'KE',
            'institution_name' => 'Makerere',
            'year_of_birth' => '1970',
            'page_url' => $page_url,
            'referrer_url' => '',
            'education_level' => $student_academic_level,
            'education_fund' => $how_to_fund_education,
            'partner_agent' => $partner_agent
        ];
        $processed_page_url = CraydelURLHelper::process($page_url);

        $this->post('courses/lead/submit',
            $payload
            , [
                'locale' => 'en'
            ])
            ->seeJsonContains([
                'status' => true,
                'message' => LanguageTranslationHelper::translate('courses.success.lead_submitted')
            ])
            ->seeInDatabase(
                (new Leads())->getTable(), [
                    'course_code' => $this->course_code,
                    'page_url' => $page_url,
                    'utm_source' => !is_null($processed_page_url->getUtmSource()) ? $processed_page_url->getUtmSource() : '',
                    'utm_campaign' => !is_null($processed_page_url->getUtmCampaign()) ? $processed_page_url->getUtmCampaign() : '',
                    'utm_medium' => !is_null($processed_page_url->getUtmMedium()) ? $processed_page_url->getUtmMedium() : '',
                    'utm_term' => !is_null($processed_page_url->getUtmTerm()) ? $processed_page_url->getUtmTerm() : '',
                    'asset_id' => !is_null($processed_page_url->getUtmId()) ? $processed_page_url->getUtmId() : ''
                ]
            );
    }

    /**
     * Test if the lead is flagged as Direct Lead if the referrer_url is craydel of craydel sub-domain
     */
    public function testIfTheLeadIsFlaggedAsDirectLeadIfTheReferrer_urlIsCraydelOfCraydelSubdomain()
    {
        $student_academic_level = Factory::create()->company;
        $how_to_fund_education = Factory::create()->text;
        $partner_agent = Factory::create()->company;
        $page_url = 'https://craydel.com/study-destinations/study-in-canada';
        $referrer_url = $page_url;

        $payload = [
            'course_code' => $this->course_code,
            'city' => 'Nairobi',
            'country' => 'Kenya',
            'course_name' => 'Test Course',
            'email' => 'ngurujohn@gmail.com',
            'full_names' => 'John Nguru',
            'mobile_number' => '0711689928',
            'mobile_number_country_code' => 'KE',
            'institution_name' => 'Makerere',
            'year_of_birth' => '1970',
            'page_url' => $page_url,
            'referrer_url' => $referrer_url,
            'education_level' => $student_academic_level,
            'education_fund' => $how_to_fund_education,
            'partner_agent' => $partner_agent
        ];
        $processed_page_url = CraydelURLHelper::process($page_url);

        $this->post('courses/lead/submit',
            $payload
            , [
                'locale' => 'en'
            ])
            ->seeJsonContains([
                'status' => true,
                'message' => LanguageTranslationHelper::translate('courses.success.lead_submitted')
            ])
            ->seeInDatabase(
                (new Leads())->getTable(), [
                    'course_code' => $this->course_code,
                    'page_url' => $page_url,
                    'referrer_url' => $referrer_url,
                    'utm_source' => 'Direct Leads',
                    'utm_campaign' => !is_null($processed_page_url->getUtmCampaign()) ? $processed_page_url->getUtmCampaign() : '',
                    'utm_medium' => !is_null($processed_page_url->getUtmMedium()) ? $processed_page_url->getUtmMedium() : '',
                    'utm_term' => !is_null($processed_page_url->getUtmTerm()) ? $processed_page_url->getUtmTerm() : '',
                    'asset_id' => !is_null($processed_page_url->getUtmId()) ? $processed_page_url->getUtmId() : ''
                ]
            );
    }

    /**
     * Test if use cannot submit a new lead if another with the same email address exists
     */
    public function testIfUserCannotSubmitALeadWithSimilarEmailAddress()
    {
        $asset_id = Factory::create()->randomNumber(5);
        $student_academic_level = Factory::create()->company;
        $how_to_fund_education = Factory::create()->text;
        $partner_agent = Factory::create()->company;

        Leads::factory()->create([
            'email' => 'ngurujohn@gmail.com'
        ]);

        $payload = [
            'course_code' => $this->course_code,
            'city' => 'Nairobi',
            'country' => 'Kenya',
            'course_name' => 'Test Course',
            'email' => 'ngurujohn@gmail.com',
            'full_names' => 'John Nguru',
            'mobile_number' => '0711689928',
            'mobile_number_country_code' => 'KE',
            'institution_name' => 'Makerere',
            'year_of_birth' => '1970',
            'page_url' => 'https://craydel.online/courses/COAYMUHTP3W-post-graduate-diploma-in-higher-education-pgdhe/',
            'referrer_url' => 'https://craydel.online/courses/COAYMUHTP3W-post-graduate-diploma-in-higher-education-pgdhe/',
            'utm_source' => 'Facebook',
            'utm_medium' => 'Facebook',
            'utm_campaign' => 'Facebook Test Campaign',
            'dxid' => $asset_id,
            'education_level' => $student_academic_level,
            'education_fund' => $how_to_fund_education,
            'partner_agent' => $partner_agent
        ];

        $this->post('courses/lead/submit',
            $payload
            , [
                'locale' => 'en'
            ])
            ->seeJsonContains([
                'status' => true,
                'message' => LanguageTranslationHelper::translate('courses.success.lead_submitted')
            ]);

        $this->assertEquals(1, DB::table((new Leads())->getTable())->count('id'));
    }

    /**
     * Test if use cannot submit a new lead if another with the same email address exists
     */
    public function testIfUserSubmitLeadWithNullReferrerURL()
    {
        $asset_id = Factory::create()->randomNumber(5);
        $student_academic_level = Factory::create()->company;
        $how_to_fund_education = Factory::create()->text;
        $partner_agent = Factory::create()->company;

        $payload = [
            'course_code' => $this->course_code,
            'city' => 'Nairobi',
            'country' => 'Kenya',
            'course_name' => 'Test Course',
            'email' => 'ngurujohn@gmail.com',
            'full_names' => 'John Nguru',
            'mobile_number' => '0711689928',
            'mobile_number_country_code' => 'KE',
            'institution_name' => 'Makerere',
            'year_of_birth' => '1970',
            'page_url' => 'https://craydel.online/courses/COAYMUHTP3W-post-graduate-diploma-in-higher-education-pgdhe/',
            'referrer_url' => '',
            'utm_source' => 'Facebook',
            'utm_medium' => 'Facebook',
            'utm_campaign' => 'Facebook Test Campaign',
            'dxid' => $asset_id,
            'education_level' => $student_academic_level,
            'education_fund' => $how_to_fund_education,
            'partner_agent' => $partner_agent
        ];

        $course = Course::all()->where('course_code', $this->course_code)->first();

        $this->post('courses/lead/submit',
            $payload
            , [
                'locale' => 'en'
            ])
            ->seeJsonContains([
                'status' => true,
                'message' => LanguageTranslationHelper::translate('courses.success.lead_submitted')
            ])
            ->seeInDatabase(
                (new Leads())->getTable(), [
                    'course_code' => $this->course_code,
                    'city' => 'Nairobi',
                    'email' => 'ngurujohn@gmail.com',
                    'institution_name' => 'Makerere',
                    'year_of_birth' => '1970',
                    'page_url' => 'https://craydel.online/courses/COAYMUHTP3W-post-graduate-diploma-in-higher-education-pgdhe/'
                ]
            );
    }

    /**
     * Test if use cannot submit a new lead if another with the same mobile number exists
     */
    public function testIfUserCannotSubmitALeadWithSimilarMobileNumber()
    {
        $asset_id = Factory::create()->randomNumber(5);
        $student_academic_level = Factory::create()->company;
        $how_to_fund_education = Factory::create()->text;
        $partner_agent = Factory::create()->company;

        Leads::factory()->create([
            'mobile_number' => ParsePhoneNumberHelper::makeNationalizedMobileNumber('KE', '0711689928')
        ]);

        $payload = [
            'course_code' => $this->course_code,
            'city' => 'Nairobi',
            'country' => 'Kenya',
            'course_name' => 'Test Course',
            'email' => 'ngurujohn@gmail.com',
            'full_names' => 'John Nguru',
            'mobile_number' => '0711689928',
            'mobile_number_country_code' => 'KE',
            'institution_name' => 'Makerere',
            'year_of_birth' => '1970',
            'page_url' => 'https://craydel.online/courses/COAYMUHTP3W-post-graduate-diploma-in-higher-education-pgdhe/',
            'referrer_url' => 'https://craydel.online/courses/COAYMUHTP3W-post-graduate-diploma-in-higher-education-pgdhe/',
            'utm_source' => 'Facebook',
            'utm_medium' => 'Facebook',
            'utm_campaign' => 'Facebook Test Campaign',
            'dxid' => $asset_id,
            'education_level' => $student_academic_level,
            'education_fund' => $how_to_fund_education,
            'partner_agent' => $partner_agent
        ];

        $this->post('courses/lead/submit',
            $payload
            , [
                'locale' => 'en'
            ])
            ->seeJsonContains([
                'status' => true,
                'message' => LanguageTranslationHelper::translate('courses.success.lead_submitted')
            ]);

        $this->assertEquals(1, DB::table((new Leads())->getTable())->count('id'));
    }

    /**
     * Test if user can submit with
     */
    public function testIfUserCanSubmitLeadWithCourseCategoryAndLeadSource()
    {
        $asset_id = Factory::create()->randomNumber(5);
        $student_academic_level = Factory::create()->company;
        $how_to_fund_education = Factory::create()->text;
        $partner_agent = Factory::create()->company;
        $course_category = Factory::create()->company;
        $lead_source = Factory::create()->company;

        $payload = [
            'city' => 'Nairobi',
            'country' => 'Kenya',
            'course_name' => 'Test Course',
            'email' => 'ngurujohn@gmail.com',
            'full_names' => 'John Nguru',
            'mobile_number' => '0711689928',
            'mobile_number_country_code' => 'KE',
            'institution_name' => 'Makerere',
            'year_of_birth' => '1970',
            'page_url' => 'https://craydel.online/courses/COAYMUHTP3W-post-graduate-diploma-in-higher-education-pgdhe/',
            'referrer_url' => 'https://craydel.online/courses/COAYMUHTP3W-post-graduate-diploma-in-higher-education-pgdhe/',
            'education_level' => $student_academic_level,
            'education_fund' => $how_to_fund_education,
            'partner_agent' => $partner_agent,
            'course_category' => $course_category,
            'lead_source' => $lead_source
        ];

        $this->post('courses/lead/submit',
            $payload
            , [
                'locale' => 'en'
            ])
            ->seeJsonContains([
                'status' => true,
                'message' => LanguageTranslationHelper::translate('courses.success.lead_submitted')
            ])
            ->seeInDatabase(
                (new Leads())->getTable(), [
                    'city' => 'Nairobi',
                    'email' => 'ngurujohn@gmail.com',
                    'institution_name' => 'Makerere',
                    'year_of_birth' => '1970',
                    'page_url' => 'https://craydel.online/courses/COAYMUHTP3W-post-graduate-diploma-in-higher-education-pgdhe/',
                    'referrer_url' => 'https://craydel.online/courses/COAYMUHTP3W-post-graduate-diploma-in-higher-education-pgdhe/',
                    'student_academic_level' => $student_academic_level,
                    'how_to_fund_education' => $how_to_fund_education,
                    'partner_agent' => $partner_agent,
                    'course_name' => $course_category,
                    'course_category' => $course_category,
                    'default_lead_source' => $lead_source
                ]
            );
    }

    /**
     * Test if user can submit with search to conversion tracking
     */
    public function testIfUserCanSubmitLeadWithSearchToConversionTracking()
    {
        $asset_id = Factory::create()->randomNumber(5);
        $student_academic_level = Factory::create()->company;
        $how_to_fund_education = Factory::create()->text;
        $partner_agent = Factory::create()->company;
        $course_category = Factory::create()->company;
        $lead_source = Factory::create()->company;

        $payload = [
            'city' => 'Nairobi',
            'country' => 'Kenya',
            'course_name' => 'Test Course',
            'email' => 'ngurujohn@gmail.com',
            'full_names' => 'John Nguru',
            'mobile_number' => '0711689928',
            'mobile_number_country_code' => 'KE',
            'institution_name' => 'Makerere',
            'year_of_birth' => '1970',
            'page_url' => 'https://craydel.online/courses/COAYMUHTP3W-post-graduate-diploma-in-higher-education-pgdhe/',
            'referrer_url' => 'https://craydel.online/courses/COAYMUHTP3W-post-graduate-diploma-in-higher-education-pgdhe/',
            'education_level' => $student_academic_level,
            'education_fund' => $how_to_fund_education,
            'partner_agent' => $partner_agent,
            'course_category' => $course_category,
            'lead_source' => $lead_source,
            'form_type' => 'BOOK_FREE_COUNSELLING_LEAD_PAGE',
            'page_section' => 'HOME_PAGE_FIRST_FOLD',
            'query_id' => 'FFFF667676GGG',
            'search_term' => 'AXAXAXA'
        ];

        $this->post('courses/lead/submit',
            $payload
            , [
                'locale' => 'en'
            ])
            ->seeJsonContains([
                'status' => true,
                'message' => LanguageTranslationHelper::translate('courses.success.lead_submitted')
            ])
            ->seeInDatabase(
                (new Leads())->getTable(), [
                    'city' => 'Nairobi',
                    'email' => 'ngurujohn@gmail.com',
                    'institution_name' => 'Makerere',
                    'year_of_birth' => '1970',
                    'page_url' => 'https://craydel.online/courses/COAYMUHTP3W-post-graduate-diploma-in-higher-education-pgdhe/',
                    'referrer_url' => 'https://craydel.online/courses/COAYMUHTP3W-post-graduate-diploma-in-higher-education-pgdhe/',
                    'student_academic_level' => $student_academic_level,
                    'how_to_fund_education' => $how_to_fund_education,
                    'partner_agent' => $partner_agent,
                    'course_name' => $course_category,
                    'default_lead_source' => $lead_source,
                    'form_type' => 'BOOK_FREE_COUNSELLING_LEAD_PAGE',
                    'page_section' => 'HOME_PAGE_FIRST_FOLD',
                    'marketplace_search_query_id' => 'FFFF667676GGG',
                    'marketplace_search_term' => 'AXAXAXA'
                ]
            );
    }

    /**
     * Test if system can push the lead to the lead management provider
     */
    public function testIfUserCanPushLeadToTheLeadManagementProviderAndDelete()
    {
        Leads::factory()->count(1)->create([
            'course_code' => $this->course_code
        ]);

        $lead_provider = App::make(ILeadProvider::class);
        $lead = new LeadType(1);
        $result = $lead_provider->submit(new LeadTypeCollection($lead));
        $this->assertTrue($result->status);

        $lead = new LeadType(1);
        $result = LeadManagementController::deleteLeadFromCRM($lead->getLmsProviderLeadId());
        $this->assertTrue($result->status);
    }

    /**
     * Test if the user received an email when a lead has been submitted to the CRM
     */
    public function testIfUserReceivesAnEmailWhenTheLeadHasBeenSubmittedToTheCRM()
    {
        Leads::factory()->count(1)->create([
            'course_code' => $this->course_code,
            'email' => 'ngurujohn@gmail.com'
        ]);

        Event::fake();

        $this->artisan('bulk:leads:push-to-lms-provider');

        $crm_lead_id = DB::table((new Leads())->getTable())
            ->where('id', 1)
            ->value('lms_provider_lead_id');

        Event::assertDispatched(CourseApplicationLeadSubmittedToCRMEvent::class, function ($event) use ($crm_lead_id) {
            return $event->crm_lead_id == $crm_lead_id;
        });

        LeadManagementController::deleteLeadFromCRM($crm_lead_id);
    }

    /**
     * Test if the total_leads_submitted_to_crm column is incremented after the lead is submitted to the CRM
     * @throws Exception
     */
    public function testIfTheTotalLeadsSubmittedToCRMColumnIsIncrementedAfterTheLeadIsSubmittedToTheCRM()
    {
        Course::where('id', 1)->update([
            'total_leads_submitted_to_crm' => 0
        ]);

        Leads::factory()->count(1)->create([
            'course_code' => $this->course_code,
            'email' => 'ngurujohn@gmail.com'
        ]);

        Event::fake();

        $this->artisan('bulk:leads:push-to-lms-provider');

        $crm_lead_id = DB::table((new Leads())->getTable())
            ->where('id', 1)
            ->value('lms_provider_lead_id');

        Event::assertDispatched(CourseApplicationLeadSubmittedToCRMEvent::class, function ($event) use ($crm_lead_id) {
            return $event->crm_lead_id == $crm_lead_id;
        });

        (new UpdateCourseStaticsCommandController())->updateLeadCountAfterLeadIsSubmittedToCRM($crm_lead_id);

        $this->seeInDatabase((new Course())->getTable(), [
            'course_code' => $this->course_code,
            'total_leads_submitted_to_crm' => 1
        ]);

        LeadManagementController::deleteLeadFromCRM($crm_lead_id);
    }

    /**
     * Test if the lead is created it does not exist in the leads table
     */
    public function testIfLeadIsCreatedIfLeadDoesNotExistInTheLeadsTable()
    {
        $crm_lead_id = '4840299000004404020';

        $lead_details = [
            'lead_owner_id' => '4840299000000307001',
            'updated_at' => '2021-09-14 14:15:44',
            'phone' => '',
            'updated_by' => 'Craydel Admin',
            'mobile' => '0711689938',
            'created_at' => '2021-09-14 17:15:44',
            'last_name' => 'Nguru',
            'first_name' => 'John',
            'lead_id' => $crm_lead_id,
            'email' => 'ngurujohn@gmail.com'
        ];

        Event::fake();

        $this->post('courses/events/lead-updates',
            $lead_details, [
                'locale' => 'en'
            ])
            ->seeInDatabase(
                (new Leads())->getTable(), [
                    'email' => 'ngurujohn@gmail.com',
                    'lms_provider_lead_id' => $crm_lead_id,
                    'first_name' => 'John',
                    'last_name' => 'Nguru',
                    'created_by' => 'Craydel Admin',
                    'mobile_number' => '0711689938',
                    'created_at' => '2021-09-14 17:15:44',
                    'updated_at' => '2021-09-14 14:15:44',
                    'is_processed' => 1,
                    'is_picked_for_processing' => 1,
                    'lms_provider' => config('craydel.default_lms_provider')
                ]
            );

        Event::assertDispatched(CourseApplicationLeadSubmittedToCRMEvent::class, function ($event) use ($crm_lead_id) {
            return $event->crm_lead_id == $crm_lead_id;
        });
    }

    /**
     * Test if the lead is not created if the email address exists in the leads table
     */
    public function testIfLeadIsNotCreatedIfLeadExistInTheLeadsTable()
    {
        $crm_lead_id = '4840299000004404020';

        Leads::factory()->count(1)->create([
            'email' => 'ngurujohn@gmail.com'
        ]);

        $lead_details = [
            'lead_owner_id' => '4840299000000307001',
            'updated_at' => '2021-09-14 14:15:44',
            'phone' => '',
            'updated_by' => 'Craydel Admin',
            'mobile' => '0711689938',
            'created_at' => '2021-09-14 17:15:44',
            'last_name' => 'Nguru',
            'first_name' => 'John',
            'lead_id' => $crm_lead_id,
            'email' => 'ngurujohn@gmail.com'
        ];

        $this->post('courses/events/lead-updates',
            $lead_details, [
                'locale' => 'en'
            ])
            ->seeInDatabase(
                (new Leads())->getTable(), [
                    'email' => 'ngurujohn@gmail.com',
                    'first_updated_on' => '2021-09-14 14:15:44'
                ]
            );
    }

    /**
     * Test if the lead first_updated_on column when the lead first actioned on the CRM
     */
    public function testIfTheLeadIsUpdatedWhenTheLeadIsFirstActionedOnTheCRM()
    {
        $crm_lead_id = '4840299000004404020';

        Leads::factory()->count(1)->create([
            'course_code' => $this->course_code,
            'email' => 'ngurujohn@gmail.com',
            'lms_provider_lead_id' => $crm_lead_id
        ]);

        $lead_details = [
            'lead_owner_id' => '4840299000000307001',
            'updated_at' => '2021-09-14 14:15:44',
            'phone' => '',
            'updated_by' => 'Craydel Admin',
            'mobile' => '0711689938',
            'created_at' => '2021-09-13 17:15:44',
            'last_name' => 'Nguru',
            'first_name' => 'John',
            'lead_id' => $crm_lead_id,
            'email' => 'ngurujohn@gmail.com'
        ];

        Queue::fake();

        $this->post('courses/events/lead-updates',
            $lead_details, [
                'locale' => 'en'
            ])
            ->seeInDatabase(
                (new Leads())->getTable(), [
                    'email' => 'ngurujohn@gmail.com',
                    'first_updated_on' => '2021-09-14 14:15:44',
                    'updated_by' => 'Craydel Admin'
                ]
            );

        Queue::assertPushed(UpdateLeadFirstContactDate::class, function ($job) use ($crm_lead_id) {
            return $job->lms_provider_lead_id == $crm_lead_id;
        });
    }

    /**
     * Test if the lead first_updated_on column when the lead first actioned on the CRM
     */
    public function testIfTheLeadIsNotUpdatedWhenTheLeadIsSubsequentlyActionedOnTheCRM()
    {
        $crm_lead_id = '4840299000004404020';
        $first_updated_on = '2021-09-14 14:15:44';

        Leads::factory()->count(1)->create([
            'course_code' => $this->course_code,
            'email' => 'ngurujohn@gmail.com',
            'lms_provider_lead_id' => $crm_lead_id,
            'first_updated_on' => $first_updated_on
        ]);

        $lead_details = [
            'lead_owner_id' => '4840299000000307001',
            'updated_at' => '2021-09-15 14:15:44',
            'phone' => '',
            'updated_by' => 'Craydel Admin',
            'mobile' => '0711689938',
            'created_at' => '2021-09-13 17:15:44',
            'last_name' => 'Nguru',
            'first_name' => 'John',
            'lead_id' => $crm_lead_id,
            'email' => 'ngurujohn@gmail.com'
        ];

        $this->post('courses/events/lead-updates',
            $lead_details, [
                'locale' => 'en'
            ])
            ->seeInDatabase(
                (new Leads())->getTable(), [
                    'lms_provider_lead_id' => $crm_lead_id,
                    'first_updated_on' => $first_updated_on
                ]
            );
    }

    /**
     * Test if lead updates are sent to the data lake when the lead status changes
     */
    public function testIfLeadUpdatesAreSentToTheDataLakeWhenTheLeadsArePushedToTheCRM()
    {
        Leads::factory()->count(1)->create([
            'course_code' => $this->course_code,
            'email' => 'ngurujohn@gmail.com'
        ]);

        Queue::fake();

        $this->artisan('bulk:leads:push-to-lms-provider');

        Queue::assertPushed(PushLeadAndOpportunitiesDataToDateLakeJob::class);
    }

    /**
     * Test if you can add the lead without the phone number
     */
    public function testIfYouCanAddALeadWithoutThePhoneNumber()
    {
        $student_academic_level = Factory::create()->company;
        $how_to_fund_education = Factory::create()->text;
        $partner_agent = Factory::create()->company;
        $page_url = 'https://craydel.com/study-destinations/study-in-canada';

        $payload = [
            'course_code' => $this->course_code,
            'city' => 'Nairobi',
            'country' => 'Kenya',
            'course_name' => 'Test Course',
            'email' => 'ngurujohn@gmail.com',
            'full_names' => 'John Nguru',
            'mobile_number_country_code' => 'KE',
            'institution_name' => 'Makerere',
            'year_of_birth' => '1970',
            'page_url' => $page_url,
            'referrer_url' => '',
            'education_level' => $student_academic_level,
            'education_fund' => $how_to_fund_education,
            'partner_agent' => $partner_agent
        ];
        $processed_page_url = CraydelURLHelper::process($page_url);

        $this->post('courses/lead/submit',
            $payload
            , [
                'locale' => 'en'
            ])
            ->seeJsonContains([
                'status' => false,
                'message' => 'Missing or Invalid mobile number'
            ])
            ->notSeeInDatabase(
                (new Leads())->getTable(), [
                    'course_code' => $this->course_code,
                    'page_url' => $page_url,
                    'utm_source' => !is_null($processed_page_url->getUtmSource()) ? $processed_page_url->getUtmSource() : '',
                    'utm_campaign' => !is_null($processed_page_url->getUtmCampaign()) ? $processed_page_url->getUtmCampaign() : '',
                    'utm_medium' => !is_null($processed_page_url->getUtmMedium()) ? $processed_page_url->getUtmMedium() : '',
                    'utm_term' => !is_null($processed_page_url->getUtmTerm()) ? $processed_page_url->getUtmTerm() : '',
                    'asset_id' => !is_null($processed_page_url->getUtmId()) ? $processed_page_url->getUtmId() : ''
                ]
            );

    }

    /**
     * Test if you can add the lead without the country code
     */
    public function testIfYouCanAddALeadWithoutCountryCode()
    {
        $student_academic_level = Factory::create()->company;
        $how_to_fund_education = Factory::create()->text;
        $partner_agent = Factory::create()->company;
        $page_url = 'https://craydel.com/study-destinations/study-in-canada';

        $payload = [
            'course_code' => $this->course_code,
            'city' => 'Nairobi',
            'country' => 'Kenya',
            'course_name' => 'Test Course',
            'email' => 'ngurujohn@gmail.com',
            'full_names' => 'John Nguru',
            'mobile_number' => '254715672738',
            'institution_name' => 'Makerere',
            'year_of_birth' => '1970',
            'page_url' => $page_url,
            'referrer_url' => '',
            'education_level' => $student_academic_level,
            'education_fund' => $how_to_fund_education,
            'partner_agent' => $partner_agent
        ];
        $processed_page_url = CraydelURLHelper::process($page_url);

        $this->post('courses/lead/submit',
            $payload
            , [
                'locale' => 'en'
            ])
            ->seeJsonContains([
                'status' => true,
                LanguageTranslationHelper::translate('courses.success.lead_submitted')
            ])
            ->SeeInDatabase(
                (new Leads())->getTable(), [
                    'course_code' => $this->course_code,
                    'page_url' => $page_url,
                    'utm_source' => !is_null($processed_page_url->getUtmSource()) ? $processed_page_url->getUtmSource() : '',
                    'utm_campaign' => !is_null($processed_page_url->getUtmCampaign()) ? $processed_page_url->getUtmCampaign() : '',
                    'utm_medium' => !is_null($processed_page_url->getUtmMedium()) ? $processed_page_url->getUtmMedium() : '',
                    'utm_term' => !is_null($processed_page_url->getUtmTerm()) ? $processed_page_url->getUtmTerm() : '',
                    'asset_id' => !is_null($processed_page_url->getUtmId()) ? $processed_page_url->getUtmId() : ''
                ]
            );

    }

    /**
     * Test if you can add the lead with just name,email,phone and page_url
     */
    public function testIfYouCanAddALeadWithNameEmailPhoneNumberPageURL()
    {
        $page_url = 'https://craydel.com/study-destinations/study-in-canada';

        $payload = [
            'email' => 'ngurujohn@gmail.com',
            'full_names' => 'John Nguru',
            'mobile_number' => '0715672738',
            'mobile_number_country_code' => 'KE',
            'city' => 'Nairobi',
            'page_url' => $page_url,

        ];
        $processed_page_url = CraydelURLHelper::process($page_url);

        $this->post('courses/lead/submit',
            $payload
            , [
                'locale' => 'en'
            ])
            ->seeJsonContains([
                'status' => true,
                LanguageTranslationHelper::translate('courses.success.lead_submitted')
            ])
            ->SeeInDatabase(
                (new Leads())->getTable(), [
                    'email' => 'ngurujohn@gmail.com',
                    'first_name' => 'John',
                    'last_name' => 'Nguru',
                    'mobile_number' => '+254715672738',
                    'city' => 'Nairobi',
                    'page_url' => $page_url
                ]
            );

    }

    /**
     * Test if we can update the lead when similar details were added in the chat
    */
    public function testIfUserUpdateSubmitALeadWithSimilarEmailAddressFromChatBox()
    {
        $asset_id = Factory::create()->randomNumber(5);
        $student_academic_level = Factory::create()->company;
        $how_to_fund_education = Factory::create()->text;
        $partner_agent = Factory::create()->company;

        Leads::factory()->create([
            'email' => 'ngurujohn@gmail.com',
        ]);

        $payload = [
            'course_code' => $this->course_code,
            'city' => 'Nairobi',
            'country' => 'Kenya',
            'course_name' => 'Test Course Two',
            'email' => 'ngurujohn@gmail.com',
            'full_names' => 'John Nguru',
            'mobile_number' => '0711689928',
            'mobile_number_country_code' => 'KE',
            'institution_name' => 'Makerere Uni',
            'year_of_birth' => Carbon::now()->subYears(12)->toDateString(),
            'page_url' => 'https://craydel.online/courses/COAYMUHTP3W-post-graduate-diploma-in-higher-education-pgdhe/',
            'referrer_url' => 'https://craydel.online/courses/COAYMUHTP3W-post-graduate-diploma-in-higher-education-pgdhe/',
            'utm_source' => 'automated_chat_lead_form',
            'utm_medium' => 'Facebook',
            'utm_campaign' => 'Facebook Test Campaign',
            'dxid' => $asset_id,
            'education_level' => $student_academic_level,
            'education_fund' => $how_to_fund_education,
            'partner_agent' => $partner_agent
        ];

        $this->post('courses/lead/submit',
            $payload
            , [
                'locale' => 'en'
            ])
            ->seeJsonContains([
                'status' => true,
                'message' => LanguageTranslationHelper::translate('courses.success.lead_submitted')
            ]);

        $this->assertEquals(1, DB::table((new Leads())->getTable())->count('id'));

    }

    /**
     * Test if user can submit a lead
     */
    public function testIfCanSubmitTheProgressiveLeadForm()
    {
        $lead = Leads::factory()->create([
            'course_name' => 'Not a real course name'
        ])->first();

        $course_name = Factory::create()->company;
        $student_has_passport = 'Yes';
        $target_intake = "Jan";
        $target_destination = Factory::create()->country;
        $target_budget = 'Less than 5,000 USD';
        $education_fund = 'Someone Else';
        $target_year = Carbon::now()->format('Y');

        $payload = [
            'lead_id' => $lead->id,
            'course_name' => $course_name,
            'student_has_passport' => $student_has_passport,
            'target_intake' => $target_intake,
            'target_destination' => $target_destination,
            'target_budget' => $target_budget,
            'education_fund' => $education_fund,
            'target_year' => $target_year
        ];

        $this->post('courses/lead/submit-progressive-lead-form',
            $payload
            , [
                'locale' => 'en'
            ])
            ->seeJsonContains([
                'status' => true,
                'message' => LanguageTranslationHelper::translate('courses.success.lead_submitted')
            ])
            ->seeInDatabase(
                (new Leads())->getTable(), [
                    'course_name' => $course_name,
                    'student_has_passport' => $student_has_passport,
                    'used_progressive_lead_form' => 'Yes',
                    'current_course_intake' => $target_intake . '-' .$target_year,
                    'target_budget' => $target_budget,
                    'study_destination' => $target_destination,
                    'how_to_fund_education' => $education_fund
                ]
            );
    }
}
