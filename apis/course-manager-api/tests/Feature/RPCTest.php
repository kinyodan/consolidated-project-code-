<?php
namespace Feature;

use App\Http\Controllers\CraydelTypes\LeadType;
use App\Http\Controllers\CraydelTypes\LeadTypeCollection;
use App\Http\Controllers\Helpers\CraydelHelperFunctions;
use App\Http\Controllers\Providers\LeadManagement\ILeadProvider;
use App\Http\Controllers\Providers\LeadManagement\LeadManagementController;
use App\Models\Course;
use App\Models\CourseAcademicDiscipline;
use App\Models\Leads;
use Faker\Factory;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use TestCase;
use TestingHelperFunction;

class RPCTest extends TestCase
{
    use TestingHelperFunction;

    /**
     * @var string|null $institution_code
    */
    public ?string $institution_code = 'INTANHXXFAYVR';

    /**
     * @var $course
    */
    public $course;

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

        $this->course_code = 'CO2CM6B2SBP';

        $this->course = Course::factory()->count(1)->create([
            'course_code' => $this->course_code,
            'institution_code' => $this->institution_code,
            'is_deleted' => 0,
            'has_updates' => 1,
            'is_active' => 1,
            'is_picked_for_indexing' => 0,
            'course_small_image' => 'https://www.dancarlin.com/hardcore-history-56-kings-of-kings/',
            'course_image' => 'https://www.dancarlin.com/hardcore-history-56-kings-of-kings/'
        ])->first();
    }

    /**
     * Test if rpc can return the lead details based on the leads email address
     */
    public function testIfCanGetTheLeadDetailsBasedOnTheLeadEmailAddress(){
        $email_address = 'ngurujohn@gmail.com';

        Leads::factory()->count(1)->create([
            'email' => 'ngurujohn@gmail.com'
        ]);

        $this->get('leads/rpc/details/'.$email_address)
            ->seeJsonContains([
                'status' => true,
                'message' => 'Lead details'
            ])->seeJsonStructure([
                'data' => [
                    'id',
                    'course_code',
                    'mobile_number',
                    'email',
                    'city',
                    'country',
                    'first_name',
                    'last_name',
                    'year_of_birth',
                    'course_name',
                    'course_learning_mode',
                    'current_course_intake',
                    'student_academic_level',
                    'how_to_fund_education',
                    'institution_name',
                    'partner_agent',
                    'page_url',
                    'referrer_url',
                    'utm_source',
                    'utm_medium',
                    'utm_campaign',
                    'utm_term',
                    'ad_id',
                    'ad_set_id',
                    'campaign_id',
                    'ad_name',
                    'ad_set_name',
                    'ad_placement_position',
                    'site_source_name',
                    'utm_content',
                    'form_type',
                    'page_section',
                    'marketplace_search_term',
                    'marketplace_search_query_id',
                    'asset_id',
                    'default_lead_source',
                    'lead_status',
                    'lms_provider_lead_id'
                ]
            ]);
    }

    /**
     * Test if you can fetch the lead details from CRM via an RPC call
    */
    public function testIfCanFetchTheLeadDetailsFromCRMViaAnRPCUsingEmailAddress(){
        $email_address = 'ngurujohn@gmail.com';

        Leads::factory()->count(1)->create([
            'course_code' => $this->course_code,
            'email' => $email_address
        ]);

        $lead_provider = App::make(ILeadProvider::class);
        $lead = new LeadType(1);
        $lead_provider->submit(new LeadTypeCollection($lead));

        $this->get('leads/rpc/details/from-crm-by-email/'.$email_address)
            ->seeJsonContains([
                'status' => true
            ])->seeJsonStructure([
                'data' => [
                    'Owner' => [
                        'name',
                        'id',
                        'email'
                    ],
                    'Email',
                    'Advert_Set_ID',
                    'Study_type',
                    'What_budget_per_year_do_you_have_for_your_studies',
                    'Advert_ID',
                    'Last_Activity_Time',
                    'Who_would_be_sponsoring_your_studies',
                    'Unsubscribed_Mode',
                    'Course_Current_Intake',
                    'Campaign_ID',
                    'id',
                    'Advert_Site_Source_Name',
                    'Campaign_Term',
                    'Created_Time',
                    'Page_URL',
                    'How_Will_You_Your_Education',
                    'Source_Campaign',
                    'Student_Academic_Level',
                    'Referrer_URL',
                    'Campaign_Asset',
                    'Created_By' => [
                        'name',
                        'id',
                        'email'
                    ],
                    'Annual_Revenue',
                    'Course_Learning_Mode',
                    'Description',
                    'Advert_Placement',
                    'Best_time_to_call',
                    'Campaign_Name',
                    'Institution_Name',
                    'Course_Graduate_Levels',
                    'Course_Name',
                    'Lead_First_Contacted_On',
                    'Marketplace_Search_Term',
                    'Salutation',
                    'First_Name',
                    'Full_Name',
                    'Lead_Status',
                    'Record_Image',
                    'Which_Intake',
                    'Modified_By' => [
                        'name',
                        'id',
                        'email'
                    ],
                    'Why_customer_is_not_interested',
                    'Lead_Conversion_Time',
                    'Marketplace_Search_Query_ID',
                    'Phone',
                    'Modified_Time',
                    'What_level_of_study_are_you_applying_for',
                    'Would_you_like_to_take_a_psychometric_assessment',
                    'Referred_By',
                    'Unsubscribed_Time',
                    'Campaign_Content',
                    'Year_of_Birth',
                    'Mobile',
                    'Lead_Medium',
                    'Advert_Name',
                    'Have_you_decided_on_the_program_you_want_to_take',
                    'How_did_you_hear_about_Craydel',
                    'Advert_Set_Name',
                    'Last_Name',
                    'Landing_Page_Section',
                    'Study_Destination',
                    'Lead_Source'
                ]
            ]);

        $lead = new LeadType(1);
        $result = LeadManagementController::deleteLeadFromCRM($lead->getLmsProviderLeadId());
        $this->assertTrue($result->status);
    }

    /**
     * Test if you can fetch the lead details from CRM via an RPC call
    */
    public function testIfCanFetchTheLeadDetailsFromCRMViaAnRPCUsingCMSLeadID(){
        $email_address = 'ngurujohn@gmail.com';

        Leads::factory()->count(1)->create([
            'course_code' => $this->course_code,
            'email' => $email_address
        ]);

        $lead_provider = App::make(ILeadProvider::class);
        $lead = new LeadType(1);
        $lead_provider->submit(new LeadTypeCollection($lead));
        $lead = new LeadType(1);

        $this->get('leads/rpc/details/from-crm-by-lead-id/'.$lead->getLmsProviderLeadId())
            ->seeJsonContains([
                'status' => true
            ])->seeJsonStructure([
                'data' => [
                    'Owner' => [
                        'name',
                        'id',
                        'email'
                    ],
                    'Email',
                    'Advert_Set_ID',
                    'Study_type',
                    'What_budget_per_year_do_you_have_for_your_studies',
                    'Advert_ID',
                    'Last_Activity_Time',
                    'Who_would_be_sponsoring_your_studies',
                    'Unsubscribed_Mode',
                    'Course_Current_Intake',
                    'Campaign_ID',
                    'id',
                    'Advert_Site_Source_Name',
                    'Campaign_Term',
                    'Created_Time',
                    'Page_URL',
                    'How_Will_You_Your_Education',
                    'Source_Campaign',
                    'Student_Academic_Level',
                    'Referrer_URL',
                    'Campaign_Asset',
                    'Created_By' => [
                        'name',
                        'id',
                        'email'
                    ],
                    'Annual_Revenue',
                    'Course_Learning_Mode',
                    'Description',
                    'Advert_Placement',
                    'Best_time_to_call',
                    'Campaign_Name',
                    'Institution_Name',
                    'Course_Graduate_Levels',
                    'Course_Name',
                    'Lead_First_Contacted_On',
                    'Marketplace_Search_Term',
                    'Salutation',
                    'First_Name',
                    'Full_Name',
                    'Lead_Status',
                    'Record_Image',
                    'Which_Intake',
                    'Modified_By' => [
                        'name',
                        'id',
                        'email'
                    ],
                    'Why_customer_is_not_interested',
                    'Lead_Conversion_Time',
                    'Marketplace_Search_Query_ID',
                    'Phone',
                    'Modified_Time',
                    'What_level_of_study_are_you_applying_for',
                    'Would_you_like_to_take_a_psychometric_assessment',
                    'Referred_By',
                    'Unsubscribed_Time',
                    'Campaign_Content',
                    'Year_of_Birth',
                    'Mobile',
                    'Lead_Medium',
                    'Advert_Name',
                    'Have_you_decided_on_the_program_you_want_to_take',
                    'How_did_you_hear_about_Craydel',
                    'Advert_Set_Name',
                    'Last_Name',
                    'Landing_Page_Section',
                    'Study_Destination',
                    'Lead_Source'
                ]
            ]);

        $lead = new LeadType(1);
        $result = LeadManagementController::deleteLeadFromCRM($lead->getLmsProviderLeadId());
        $this->assertTrue($result->status);
    }

    /**
     * Test if you can fetch the lead ID from DB via an RPC call
    */
    public function testIfCanFetchTheLeadIDFromDBViaAnRPCCall(){
        $email_address = 'ngurujohn@gmail.com';

        Leads::factory()->count(1)->create([
            'course_code' => $this->course_code,
            'email' => $email_address,
            'lms_provider_lead_id' => Factory::create()->randomNumber(5)
        ]);

        $lead = new LeadType(1);

        $response = $this->get('leads/rpc/details/by-lead-identifier/'.$lead->getEmail())
            ->seeJsonContains([
                'status' => true
            ])->seeJsonStructure([
                'data' => [
                    'lms_provider_lead_id'
                ]
            ]);

        $response = json_decode($response->response->getContent());
        $this->assertTrue(!CraydelHelperFunctions::isNull($response->data->lms_provider_lead_id));
    }

    /**
     * Test if RPC can fetch the number of courses that an institution has
    */
    public function testIfCanGetTheNumberOfPublishedCoursesBelongingToAnInstitution(){
        CourseAcademicDiscipline::factory()->count(2)->create();
        $this->artisan('search:course:generate-index-list');

        $response = $this->get('courses/rpc/course-count/'.$this->institution_code)
            ->seeJsonContains([
                'status' => true,
                'message' => 'Counted'
            ])->seeJsonStructure([
                'data' => [
                    'course_count'
                ]
            ]);

        $response = json_decode($response->response->getContent());
        $this->assertTrue($response->data->course_count === 2);
    }
}
