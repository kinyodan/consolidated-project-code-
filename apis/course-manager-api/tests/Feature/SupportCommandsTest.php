<?php
namespace Feature;

use App\Http\Controllers\Helpers\JWTHelper;
use App\Http\Controllers\Providers\Forex\ForexController;
use App\Models\Course;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use TestCase;
use TestingHelperFunction;

class SupportCommandsTest extends TestCase
{
    use TestingHelperFunction;

    /**
     * @var object|null
     */
    protected ?object $user;

    /**
     * @var string|null $token
    */
    protected string $token;

    /**
     * Set up the tests.
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

        $this->token = trim(file_get_contents(storage_path('test-token.txt')));
        $this->user = JWTHelper::decode($this->token);
    }

    /**
     * Test that system can do a bulk currency and fee change
    */
    public function testThatSystemCanDoABulkCurrencyAndFeeChange(){
        $standard_fee = rand(10, 100);
        $foreign_student_fee = rand(100, 1000);
        $base_currencies = ['KES', 'UGX', 'TZS', 'ZAR', 'EUR'];
        $convert_to = ['USD'];
        $selected_currency = $base_currencies[rand(0, (count($base_currencies) - 1))];
        $convert_to_currency = $convert_to[rand(0, (count($convert_to) - 1))];

        Course::factory()->count(1)->create([
            'currency' => $selected_currency,
            'standard_fee_payable' => $standard_fee,
            'foreign_student_fee_payable' => $foreign_student_fee,
            'is_deleted' => 0
        ]);

        $this->artisan('support:convert-all-course-fees');

        $convert_standard_fees = new ForexController(
            $selected_currency,
            $convert_to_currency,
            $standard_fee
        );

        $convert_foreign_fees = new ForexController(
            $selected_currency,
            $convert_to_currency,
            $foreign_student_fee
        );

        $standard_fee_control_conversion = $convert_standard_fees->convert();
        $foreign_fee_control_conversion = $convert_foreign_fees->convert();
        $course = Course::all()->first();

        $this->assertEquals($convert_to_currency, $course->currency);
        $this->assertEquals(ceil($standard_fee_control_conversion->data->converted_value), ceil($course->standard_fee_payable_usd));
        $this->assertEquals(ceil($standard_fee_control_conversion->data->converted_value), ceil($course->standard_fee_payable));
        $this->assertEquals(ceil($foreign_fee_control_conversion->data->converted_value), ceil($course->foreign_student_fee_payable_usd));
        $this->assertEquals(ceil($foreign_fee_control_conversion->data->converted_value), ceil($course->foreign_student_fee_payable));
    }

    /**
     * Test if the system can create first year course fee for both standard and foreign students
     * @throws Exception
     */
    public function testIfSystemCanCreateFirstYearCourseFeeForBothStandardAndForeignStudent(){
        $standard_fee = rand(100, 100);
        $foreign_student_fee = rand(100, 1000);
        $selected_currency = 'USD';
        $course_duration_category = ['Years', 'Months', 'Weeks', 'Days'];
        $selected_duration_category = $course_duration_category[rand(0, (count($course_duration_category) - 1))];
        $course_duration = rand(1, 10);

        Course::factory()->count(3)->create([
            'currency' => $selected_currency,
            'standard_fee_payable' => $standard_fee,
            'foreign_student_fee_payable' => $foreign_student_fee,
            'standard_fee_payable_usd' => $standard_fee,
            'foreign_student_fee_payable_usd' => $foreign_student_fee,
            'course_duration' => $course_duration,
            'course_duration_category' => $selected_duration_category,
            'is_active' => 1
        ]);

        $this->artisan('bulk:courses:generate-first-year-fees');

        $course = Course::all()->random(1)->first();
        $standard_first_year_fee_payable_usd = call_user_func(function () use($selected_duration_category, $standard_fee, $course_duration){
            if($selected_duration_category == 'Years'){
                return ceil(floatval($standard_fee/$course_duration));
            }else{
                return $standard_fee;
            }
        });

        $foreign_student_first_year_fee_payable_usd = call_user_func(function () use($selected_duration_category, $foreign_student_fee, $course_duration){
            if($selected_duration_category == 'Years'){
                return ceil(floatval($foreign_student_fee/$course_duration));
            }else{
                return $foreign_student_fee;
            }
        });

        $this->assertNotNull($course->standard_first_year_fee_payable_usd);
        $this->assertEquals($standard_first_year_fee_payable_usd, $course->standard_first_year_fee_payable_usd);
        $this->assertNotNull($course->foreign_student_first_year_fee_payable_usd);
        $this->assertEquals($foreign_student_first_year_fee_payable_usd, $course->foreign_student_first_year_fee_payable_usd);
    }
}
