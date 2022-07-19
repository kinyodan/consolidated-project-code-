<?php
namespace Feature;

use App\Events\CourseCreatedEvent;
use App\Events\CourseUpdatedEvent;
use App\Http\Controllers\Courses\Commands\CalculateFirstYearFeeCommandController;
use App\Http\Controllers\Courses\Commands\CreateCoursesFooterCommandController;
use App\Http\Controllers\Courses\Commands\PushCourseDataToInternalSearchEngineCommandController;
use App\Http\Controllers\Courses\Commands\PushCourseDataToSearchEngineCommandController;
use App\Http\Controllers\Courses\CourseController;
use App\Http\Controllers\Helpers\AcademicDisciplineHelper;
use App\Http\Controllers\Helpers\CraydelHelperFunctions;
use App\Http\Controllers\Helpers\LanguageTranslationHelper;
use App\Http\Controllers\Providers\Forex\ForexController;
use App\Models\AcademicDiscipline;
use App\Models\Course;
use App\Models\CourseAcademicDiscipline;
use App\Models\CourseSearchIndexList;
use App\Models\CourseType;
use App\Models\Leads;
use App\Models\LearningMode;
use App\Models\SelectedKeyphrase;
use Carbon\Carbon;
use Database\Factories\SelectedKeyphraseFactory;
use Exception;
use Faker\Factory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use TestCase;
use TestingHelperFunction;

class CoursesTest extends TestCase
{
  use TestingHelperFunction;

  /**
   * Test if user can build a new course request.
   *
   * @return void
   */
  public function testIfUserCanBuildANewCourseRequest()
  {
    $this->withoutExceptionHandling();
    $token = file_get_contents(storage_path('test-token.txt'));
    $this->get('courses/admin/build', [
      'token' => $token,
      'locale' => 'en'
    ])->seeJsonContains([
      'status' => true,
      'message' => LanguageTranslationHelper::translate('courses.success.built')
    ])->seeJsonStructure([
      'data' => [
        'types',
        'learning_mode',
        'attendance_type',
        'standard_fee_billing_type',
        'course_duration_category'
      ]
    ]);
  }

  /**
   * Test if user can create a new course with some null values
   *
   * @return void
   */
  public function testIfUserCanCreateANewCourseWithSomeNullValues()
  {
    $this->withoutExceptionHandling();
    Schema::disableForeignKeyConstraints();
    DB::table((new Course())->getTable())->truncate();
    DB::table((new CourseAcademicDiscipline())->getTable())->truncate();
    $this->artisan('db:seed');
    Schema::enableForeignKeyConstraints();
    $token = file_get_contents(storage_path('test-token.txt'));
    $attendance_types = [
      'Full Time', 'Evening Classes', 'Part Time'
    ];
    $billing_type = [
      'Total Cost', 'Per Term', 'Per Semester', 'Per Module', 'Per Year', 'Per Unit'
    ];
    $course_duration_category_in = [
      'Years', 'Months', 'Weeks', 'Days'
    ];
    $enrollment_details = json_encode([
      'start_month' => 'February',
      'end_month' => 'April',
      'deadline' => '2021-04-18'
    ]);
    $graduate_level = [
      'Examination',
      'Certificate',
      'Diploma',
      'Degree',
      'Associate Degree'
    ];
    $institution_code = Factory::create()->randomNumber(5);
    $course_name_slug = Factory::create()->slug(5);
    $course_name = Factory::create()->company;
    $description = Factory::create()->randomHtml(5);
    $course_overview = Factory::create()->randomHtml(5);
    $discipline_code = AcademicDiscipline::take(1)->first()->id;
    $linked_course_categories = call_user_func(function () {
      $list = DB::table((new AcademicDiscipline())->getTable())
        ->take(2)
        ->inRandomOrder()
        ->select(['id'])
        ->get()
        ->toArray();
      return json_encode(array_column($list, 'id'));
    });
    $course_type = CourseType::take(1)->first()->id;
    $institution_course_code = Factory::create()->randomNumber(5);
    $attendance_type = $attendance_types[rand(0, (count($attendance_types) - 1))];
    $learning_mode = LearningMode::take(1)->first()->id;
    $faculty_code = Factory::create()->randomNumber(5);
    $institution_website_course_url = Factory::create()->url;
    $institution_website_application_form_url = Factory::create()->url;
    $course_requirements = Factory::create()->randomHtml(5);
    $standard_fee_billing_type = $billing_type[rand(0, (count($billing_type) - 1))];
    $standard_fee_payable = number_format(Factory::create()->randomFloat(12, 2), 2);
    $standard_fee_breakdown = Factory::create()->randomHtml(5);
    $foreign_student_fee_payable = number_format(Factory::create()->randomFloat(12, 2), 2);
    $foreign_student_fee_breakdown = Factory::create()->randomHtml(5);
    $temp_course_image_url = Factory::create()->url;
    $course_structure_breakdown = Factory::create()->randomHtml(5);
    $course_duration = 4.7;
    $course_duration_category = $course_duration_category_in[rand(0, (count($course_duration_category_in) - 1))];
    $graduate_level = $graduate_level[rand(0, (count($graduate_level) - 1))];
    $maximum_scholarship_available = rand(0, 100);
    $accredited_by_acronym = 'null';
    $accreditation_organization_url = 'null';
    $meta_keywords = null;
    $meta_description = null;
    $created_at = Carbon::now()->toDateTimeString();
    $payload = [
      'country_code' => 'KE',
      'institution_code' => $institution_code,
      'course_name_slug' => $course_name_slug,
      'course_name' => $course_name,
      'description' => $description,
      'course_overview' => $course_overview,
      'linked_course_categories' => $linked_course_categories,
      'course_type' => $course_type,
      'institution_course_code' => $institution_course_code,
      'attendance_type' => $attendance_type,
      'graduate_level' => $graduate_level,
      'learning_mode' => $learning_mode,
      'faculty_code' => $faculty_code,
      'institution_website_course_url' => $institution_website_course_url,
      'institution_website_application_form_url' => $institution_website_application_form_url,
      'course_requirements' => $course_requirements,
      'content_completeness_score' => 2,
      'enrollment_details' => $enrollment_details,
      'enrollment_in_progress' => 1,
      'standard_fee_billing_type' => $standard_fee_billing_type,
      'currency' => 'USD',
      'standard_fee_payable' => $standard_fee_payable,
      'standard_fee_breakdown' => $standard_fee_breakdown,
      'foreign_student_fee_billing_type' => $billing_type[rand(0, (count($billing_type) - 1))],
      'foreign_student_fee_payable' => $foreign_student_fee_payable,
      'foreign_student_fee_breakdown' => $foreign_student_fee_breakdown,
      'temp_course_image_url' => $temp_course_image_url,
      'course_structure_breakdown' => $course_structure_breakdown,
      'course_duration' => $course_duration,
      'course_duration_category' => $course_duration_category,
      'maximum_scholarship_available' => $maximum_scholarship_available,
      'accredited_by' => $accredited_by_acronym,
      'accredited_by_acronym' => CraydelHelperFunctions::makeAcronym($accredited_by_acronym),
      'accreditation_organization_url' => $accreditation_organization_url,
      'meta_keywords' => $meta_keywords,
      'meta_description' => $meta_description,
      'created_at' => $created_at
    ];
    $this->post('courses/admin/create',
      $payload
      , [
        'token' => $token,
        'locale' => 'en'
      ])
      ->seeJsonContains([
        'status' => true,
        'message' => LanguageTranslationHelper::translate('courses.success.created')
      ])
      ->seeInDatabase(
        (new Course())->getTable(), [
          'country_code' => 'KE',
          'institution_code' => $institution_code,
          'course_name' => $course_name,
          'course_type' => $course_type,
        ]
      );
    Schema::disableForeignKeyConstraints();
    DB::table((new Course())->getTable())->truncate();
    DB::table((new CourseAcademicDiscipline())->getTable())->truncate();
    Schema::enableForeignKeyConstraints();
  }

  /**
   * Test if user can create a new course with all values
   *
   * @return void
   */
  public function testIfUserCanCreateANewCourseWithAllValues()
  {
    $this->withoutExceptionHandling();
    Schema::disableForeignKeyConstraints();
    DB::table((new Course())->getTable())->truncate();
    DB::table((new CourseAcademicDiscipline())->getTable())->truncate();
    $this->artisan('db:seed');
    Schema::enableForeignKeyConstraints();
    $token = file_get_contents(storage_path('test-token.txt'));
    $attendance_types = [
      'Full Time', 'Evening Classes', 'Part Time'
    ];
    $billing_type = [
      'Total Cost', 'Per Term', 'Per Semester', 'Per Module', 'Per Year', 'Per Unit'
    ];
    $course_duration_category_in = [
      'Years', 'Months', 'Weeks', 'Days'
    ];
    $enrollment_details = json_encode([
      'start_month' => 'February',
      'end_month' => 'April',
      'deadline' => '2021-04-18'
    ]);
    $graduate_level = [
      'Examination',
      'Certificate',
      'Diploma',
      'Degree',
      'Associate Degree'
    ];
    $institution_code = Factory::create()->randomNumber(5);
    $course_name_slug = Factory::create()->slug(5);
    $course_name = Factory::create()->company;
    $description = Factory::create()->randomHtml(5);
    $course_overview = Factory::create()->randomHtml(5);
    $linked_course_categories = call_user_func(function () {
      $list = DB::table((new AcademicDiscipline())->getTable())
        ->take(2)
        ->inRandomOrder()
        ->select(['id'])
        ->get()
        ->toArray();
      return json_encode(array_column($list, 'id'));
    });
    $course_type = CourseType::take(1)->first()->id;
    $institution_course_code = Factory::create()->randomNumber(5);
    $attendance_type = $attendance_types[rand(0, (count($attendance_types) - 1))];
    $learning_mode = LearningMode::take(1)->first()->id;
    $faculty_code = Factory::create()->randomNumber(5);
    $institution_website_course_url = Factory::create()->url;
    $institution_website_application_form_url = Factory::create()->url;
    $course_requirements = Factory::create()->randomHtml(5);
    $standard_fee_billing_type = $billing_type[rand(0, (count($billing_type) - 1))];
    $standard_fee_payable = rand(100, 999999999999);
    $standard_fee_breakdown = Factory::create()->randomHtml(5);
    $foreign_student_fee_payable = rand(100, 999999999999);
    $foreign_student_fee_breakdown = Factory::create()->randomHtml(5);
    $temp_course_image_url = Factory::create()->url;
    $course_structure_breakdown = Factory::create()->randomHtml(5);
    $course_duration = 4.7;
    $course_duration_category = $course_duration_category_in[rand(0, (count($course_duration_category_in) - 1))];
    $graduate_level = $graduate_level[rand(0, (count($graduate_level) - 1))];
    $maximum_scholarship_available = rand(0, 100);
    $accredited_by_acronym = Factory::create()->company;
    $accreditation_organization_url = Factory::create()->url;
    $meta_keywords = Factory::create()->text(50);
    $meta_description = Factory::create()->randomHtml(5);
    $created_at = Carbon::now()->toDateTimeString();
    $currency = 'USD';
    $payload = [
      'country_code' => 'KE',
      'institution_code' => $institution_code,
      'course_name_slug' => $course_name_slug,
      'course_name' => $course_name,
      'description' => $description,
      'course_overview' => $course_overview,
      'linked_course_categories' => $linked_course_categories,
      'course_type' => $course_type,
      'institution_course_code' => $institution_course_code,
      'attendance_type' => $attendance_type,
      'graduate_level' => $graduate_level,
      'learning_mode' => $learning_mode,
      'faculty_code' => $faculty_code,
      'institution_website_course_url' => $institution_website_course_url,
      'institution_website_application_form_url' => $institution_website_application_form_url,
      'course_requirements' => $course_requirements,
      'content_completeness_score' => 2,
      'enrollment_details' => $enrollment_details,
      'enrollment_in_progress' => 1,
      'standard_fee_billing_type' => $standard_fee_billing_type,
      'currency' => $currency,
      'standard_fee_payable' => $standard_fee_payable,
      'standard_fee_breakdown' => $standard_fee_breakdown,
      'foreign_student_fee_billing_type' => $billing_type[rand(0, (count($billing_type) - 1))],
      'foreign_student_fee_payable' => $foreign_student_fee_payable,
      'foreign_student_fee_breakdown' => $foreign_student_fee_breakdown,
      'temp_course_image_url' => $temp_course_image_url,
      'course_structure_breakdown' => $course_structure_breakdown,
      'course_duration' => $course_duration,
      'course_duration_category' => $course_duration_category,
      'maximum_scholarship_available' => $maximum_scholarship_available,
      'accredited_by' => $accredited_by_acronym,
      'accredited_by_acronym' => CraydelHelperFunctions::makeAcronym($accredited_by_acronym),
      'accreditation_organization_url' => $accreditation_organization_url,
      'meta_keywords' => $meta_keywords,
      'meta_description' => $meta_description,
      'created_at' => $created_at,
      'linked_blog_articles' => '[{"category":{"name":"Career Guidance","slug":"career-guidance","selected_post_ids":[702,703]}}]'
    ];
    Event::fake();
    $this->post('courses/admin/create',
      $payload, [
        'token' => $token,
        'locale' => 'en'
      ])
      ->seeJsonContains([
        'status' => true,
        'message' => LanguageTranslationHelper::translate('courses.success.created')
      ])
      ->seeInDatabase(
        (new Course())->getTable(), [
          'country_code' => 'KE',
          'institution_code' => $institution_code,
          'course_name' => $course_name,
          'course_type' => $course_type,
          'currency' => $currency,
          'graduate_level' => $graduate_level,
          'attendance_type' => $attendance_type,
          'learning_mode' => $learning_mode,
          'standard_fee_billing_type' => $standard_fee_billing_type,
          'standard_fee_payable' => round($standard_fee_payable, 2),
          'linked_blog_articles' => '[{"category":{"name":"Career Guidance","slug":"career-guidance","selected_post_ids":[702,703]}}]'
        ]
      );
    $course_id = DB::table((new Course())->getTable())->orderBy('id')->value('id');
    $this->assertCount(
      count(json_decode($linked_course_categories)),
      DB::table((new CourseAcademicDiscipline())->getTable())
        ->where('courses_id', $course_id)
        ->get()
        ->toArray()
    );
    $this->seeInDatabase((new CourseAcademicDiscipline())->getTable(), [
      'courses_id' => $course_id,
      'academic_disciplines_id' => json_decode($linked_course_categories)[0]
    ]);
    Event::assertDispatched(CourseCreatedEvent::class);
    Schema::disableForeignKeyConstraints();
    DB::table((new Course())->getTable())->truncate();
    DB::table((new CourseAcademicDiscipline())->getTable())->truncate();
    Schema::enableForeignKeyConstraints();
  }

  /**
   * Test if user can create a new course without course discipline value
   *
   * @return void
   */
  public function testIfUserCanCreateANewCourseWithoutCourseDisciplineValue()
  {
    $this->withoutExceptionHandling();
    Schema::disableForeignKeyConstraints();
    DB::table((new Course())->getTable())->truncate();
    DB::table((new CourseAcademicDiscipline())->getTable())->truncate();
    $this->artisan('db:seed');
    Schema::enableForeignKeyConstraints();
    $token = file_get_contents(storage_path('test-token.txt'));
    $attendance_types = [
      'Full Time', 'Evening Classes', 'Part Time'
    ];
    $billing_type = [
      'Total Cost', 'Per Term', 'Per Semester', 'Per Module', 'Per Year', 'Per Unit'
    ];
    $course_duration_category_in = [
      'Years', 'Months', 'Weeks', 'Days'
    ];
    $enrollment_details = json_encode([
      'start_month' => 'February',
      'end_month' => 'April',
      'deadline' => '2021-04-18'
    ]);
    $graduate_level = [
      'Examination',
      'Certificate',
      'Diploma',
      'Degree',
      'Associate Degree'
    ];
    $institution_code = Factory::create()->randomNumber(5);
    $course_name_slug = Factory::create()->slug(5);
    $course_name = Factory::create()->company;
    $description = Factory::create()->randomHtml(5);
    $course_overview = Factory::create()->randomHtml(5);
    $linked_course_categories = call_user_func(function () {
      $list = DB::table((new AcademicDiscipline())->getTable())
        ->take(2)
        ->inRandomOrder()
        ->select(['id'])
        ->get()
        ->toArray();
      return json_encode(array_column($list, 'id'));
    });
    $course_type = CourseType::take(1)->first()->id;
    $institution_course_code = Factory::create()->randomNumber(5);
    $attendance_type = $attendance_types[rand(0, (count($attendance_types) - 1))];
    $learning_mode = LearningMode::take(1)->first()->id;
    $faculty_code = Factory::create()->randomNumber(5);
    $institution_website_course_url = Factory::create()->url;
    $institution_website_application_form_url = Factory::create()->url;
    $course_requirements = Factory::create()->randomHtml(5);
    $standard_fee_billing_type = $billing_type[rand(0, (count($billing_type) - 1))];
    $standard_fee_payable = rand(100, 999999999999);
    $standard_fee_breakdown = Factory::create()->randomHtml(5);
    $foreign_student_fee_payable = rand(100, 999999999999);
    $foreign_student_fee_breakdown = Factory::create()->randomHtml(5);
    $temp_course_image_url = Factory::create()->url;
    $course_structure_breakdown = Factory::create()->randomHtml(5);
    $course_duration = 4.7;
    $course_duration_category = $course_duration_category_in[rand(0, (count($course_duration_category_in) - 1))];
    $graduate_level = $graduate_level[rand(0, (count($graduate_level) - 1))];
    $maximum_scholarship_available = rand(0, 100);
    $accredited_by_acronym = Factory::create()->company;
    $accreditation_organization_url = Factory::create()->url;
    $meta_keywords = Factory::create()->text(50);
    $meta_description = Factory::create()->randomHtml(5);
    $created_at = Carbon::now()->toDateTimeString();
    $currency = 'USD';
    $payload = [
      'country_code' => 'KE',
      'institution_code' => $institution_code,
      'course_name_slug' => $course_name_slug,
      'course_name' => $course_name,
      'description' => $description,
      'course_overview' => $course_overview,
      'linked_course_categories' => $linked_course_categories,
      'course_type' => $course_type,
      'institution_course_code' => $institution_course_code,
      'attendance_type' => $attendance_type,
      'graduate_level' => $graduate_level,
      'learning_mode' => $learning_mode,
      'faculty_code' => $faculty_code,
      'institution_website_course_url' => $institution_website_course_url,
      'institution_website_application_form_url' => $institution_website_application_form_url,
      'course_requirements' => $course_requirements,
      'content_completeness_score' => 2,
      'enrollment_details' => $enrollment_details,
      'enrollment_in_progress' => 1,
      'standard_fee_billing_type' => $standard_fee_billing_type,
      'currency' => $currency,
      'standard_fee_payable' => $standard_fee_payable,
      'standard_fee_breakdown' => $standard_fee_breakdown,
      'foreign_student_fee_billing_type' => $billing_type[rand(0, (count($billing_type) - 1))],
      'foreign_student_fee_payable' => $foreign_student_fee_payable,
      'foreign_student_fee_breakdown' => $foreign_student_fee_breakdown,
      'temp_course_image_url' => $temp_course_image_url,
      'course_structure_breakdown' => $course_structure_breakdown,
      'course_duration' => $course_duration,
      'course_duration_category' => $course_duration_category,
      'maximum_scholarship_available' => $maximum_scholarship_available,
      'accredited_by' => $accredited_by_acronym,
      'accredited_by_acronym' => CraydelHelperFunctions::makeAcronym($accredited_by_acronym),
      'accreditation_organization_url' => $accreditation_organization_url,
      'meta_keywords' => $meta_keywords,
      'meta_description' => $meta_description,
      'created_at' => $created_at,
      'linked_blog_articles' => '[{"category":{"name":"Career Guidance","slug":"career-guidance","selected_post_ids":[702,703]}}]'
    ];
    Event::fake();
    $this->post('courses/admin/create',
      $payload, [
        'token' => $token,
        'locale' => 'en'
      ])
      ->seeJsonContains([
        'status' => true,
        'message' => LanguageTranslationHelper::translate('courses.success.created')
      ])
      ->seeInDatabase(
        (new Course())->getTable(), [
          'country_code' => 'KE',
          'institution_code' => $institution_code,
          'course_name' => $course_name,
          'course_type' => $course_type,
          'currency' => $currency,
          'graduate_level' => $graduate_level,
          'attendance_type' => $attendance_type,
          'learning_mode' => $learning_mode,
          'standard_fee_billing_type' => $standard_fee_billing_type,
          'standard_fee_payable' => round($standard_fee_payable, 2),
          'linked_blog_articles' => '[{"category":{"name":"Career Guidance","slug":"career-guidance","selected_post_ids":[702,703]}}]'
        ]
      );
    $course_id = DB::table((new Course())->getTable())->orderBy('id')->value('id');
    $this->assertCount(
      count(json_decode($linked_course_categories)),
      DB::table((new CourseAcademicDiscipline())->getTable())
        ->where('courses_id', $course_id)
        ->get()
        ->toArray()
    );
    $this->seeInDatabase((new CourseAcademicDiscipline())->getTable(), [
      'courses_id' => $course_id,
      'academic_disciplines_id' => json_decode($linked_course_categories)[0]
    ]);
    Event::assertDispatched(CourseCreatedEvent::class);
    Schema::disableForeignKeyConstraints();
    DB::table((new Course())->getTable())->truncate();
    DB::table((new CourseAcademicDiscipline())->getTable())->truncate();
    Schema::enableForeignKeyConstraints();
  }

  /**``
   * Test if user can create a new course with first year fees
   *
   * @return void
   */
  public function testIfUserCanCreateANewCourseWithFirstYearFees()
  {
    $this->withoutExceptionHandling();
    Schema::disableForeignKeyConstraints();
    DB::table((new Course())->getTable())->truncate();
    DB::table((new CourseAcademicDiscipline())->getTable())->truncate();
    $this->artisan('db:seed');
    Schema::enableForeignKeyConstraints();
    $token = file_get_contents(storage_path('test-token.txt'));
    $attendance_types = [
      'Full Time', 'Evening Classes', 'Part Time'
    ];
    $billing_type = [
      'Total Cost', 'Per Term', 'Per Semester', 'Per Module', 'Per Year', 'Per Unit'
    ];
    $course_duration_category_in = [
      'Years', 'Months', 'Weeks', 'Days'
    ];
    $enrollment_details = json_encode([
      'start_month' => 'February',
      'end_month' => 'April',
      'deadline' => '2021-04-18'
    ]);
    $graduate_level = [
      'Examination',
      'Certificate',
      'Diploma',
      'Degree',
      'Associate Degree'
    ];
    $institution_code = Factory::create()->randomNumber(5);
    $course_name_slug = Factory::create()->slug(5);
    $course_name = Factory::create()->company;
    $description = Factory::create()->randomHtml(5);
    $course_overview = Factory::create()->randomHtml(5);
    $discipline_code = AcademicDiscipline::take(1)->first()->id;
    $linked_course_categories = call_user_func(function () {
      $list = DB::table((new AcademicDiscipline())->getTable())
        ->take(2)
        ->inRandomOrder()
        ->select(['id'])
        ->get()
        ->toArray();
      return json_encode(array_column($list, 'id'));
    });
    $course_type = CourseType::take(1)->first()->id;
    $institution_course_code = Factory::create()->randomNumber(5);
    $attendance_type = $attendance_types[rand(0, (count($attendance_types) - 1))];
    $learning_mode = LearningMode::take(1)->first()->id;
    $faculty_code = Factory::create()->randomNumber(5);
    $institution_website_course_url = Factory::create()->url;
    $institution_website_application_form_url = Factory::create()->url;
    $course_requirements = Factory::create()->randomHtml(5);
    $standard_fee_billing_type = $billing_type[rand(0, (count($billing_type) - 1))];
    $standard_fee_payable = rand(100, 999999999999);
    $standard_fee_breakdown = Factory::create()->randomHtml(5);
    $foreign_student_fee_payable = rand(100, 999999999999);
    $foreign_student_fee_breakdown = Factory::create()->randomHtml(5);
    $temp_course_image_url = Factory::create()->url;
    $course_structure_breakdown = Factory::create()->randomHtml(5);
    $course_duration = 4.7;
    $course_duration_category = $course_duration_category_in[rand(0, (count($course_duration_category_in) - 1))];
    $graduate_level = $graduate_level[rand(0, (count($graduate_level) - 1))];
    $maximum_scholarship_available = rand(0, 100);
    $accredited_by_acronym = Factory::create()->company;
    $accreditation_organization_url = Factory::create()->url;
    $meta_keywords = Factory::create()->text(50);
    $meta_description = Factory::create()->randomHtml(5);
    $created_at = Carbon::now()->toDateTimeString();
    $currency = 'USD';
    $standard_first_year_fee_payable_usd = Factory::create()->randomNumber(5);
    $foreign_student_first_year_fee_payable_usd = Factory::create()->randomNumber(5);
    $ignore_first_year_fees_compute_based_on_total = 1;
    $payload = [
      'country_code' => 'KE',
      'institution_code' => $institution_code,
      'course_name_slug' => $course_name_slug,
      'course_name' => $course_name,
      'description' => $description,
      'course_overview' => $course_overview,
      'linked_course_categories' => $linked_course_categories,
      'course_type' => $course_type,
      'institution_course_code' => $institution_course_code,
      'attendance_type' => $attendance_type,
      'graduate_level' => $graduate_level,
      'learning_mode' => $learning_mode,
      'faculty_code' => $faculty_code,
      'institution_website_course_url' => $institution_website_course_url,
      'institution_website_application_form_url' => $institution_website_application_form_url,
      'course_requirements' => $course_requirements,
      'content_completeness_score' => 2,
      'enrollment_details' => $enrollment_details,
      'enrollment_in_progress' => 1,
      'standard_fee_billing_type' => $standard_fee_billing_type,
      'currency' => $currency,
      'standard_fee_payable' => $standard_fee_payable,
      'standard_first_year_fee_payable_usd' => $standard_first_year_fee_payable_usd,
      'foreign_student_first_year_fee_payable_usd' => $foreign_student_first_year_fee_payable_usd,
      'standard_fee_breakdown' => $standard_fee_breakdown,
      'foreign_student_fee_billing_type' => $billing_type[rand(0, (count($billing_type) - 1))],
      'foreign_student_fee_payable' => $foreign_student_fee_payable,
      'foreign_student_fee_breakdown' => $foreign_student_fee_breakdown,
      'temp_course_image_url' => $temp_course_image_url,
      'course_structure_breakdown' => $course_structure_breakdown,
      'course_duration' => $course_duration,
      'course_duration_category' => $course_duration_category,
      'maximum_scholarship_available' => $maximum_scholarship_available,
      'accredited_by' => $accredited_by_acronym,
      'accredited_by_acronym' => CraydelHelperFunctions::makeAcronym($accredited_by_acronym),
      'accreditation_organization_url' => $accreditation_organization_url,
      'meta_keywords' => $meta_keywords,
      'meta_description' => $meta_description,
      'created_at' => $created_at,
      'ignore_first_year_fees_compute_based_on_total' => 1
    ];
    Event::fake();
    $this->post('courses/admin/create',
      $payload
      , [
        'token' => $token,
        'locale' => 'en'
      ])
      ->seeJsonContains([
        'status' => true,
        'message' => LanguageTranslationHelper::translate('courses.success.created')
      ])
      ->seeInDatabase(
        (new Course())->getTable(), [
          'country_code' => 'KE',
          'institution_code' => $institution_code,
          'course_name' => $course_name,
          'course_type' => $course_type,
          'currency' => $currency,
          'graduate_level' => $graduate_level,
          'attendance_type' => $attendance_type,
          'learning_mode' => $learning_mode,
          'standard_fee_billing_type' => $standard_fee_billing_type,
          'standard_fee_payable' => round($standard_fee_payable, 2),
          'standard_first_year_fee_payable_usd' => $standard_first_year_fee_payable_usd,
          'foreign_student_first_year_fee_payable_usd' => $foreign_student_first_year_fee_payable_usd,
          'standard_first_year_fee_payable_usd_is_manual' => 1,
          'foreign_student_first_year_fee_payable_usd_is_manual' => 1,
          'ignore_first_year_fees_compute_based_on_total' => $ignore_first_year_fees_compute_based_on_total
        ]
      );
    Event::assertDispatched(CourseCreatedEvent::class);
    Schema::disableForeignKeyConstraints();
    DB::table((new Course())->getTable())->truncate();
    DB::table((new CourseAcademicDiscipline())->getTable())->truncate();
    Schema::enableForeignKeyConstraints();
  }

  /**
   * Test if user can update course.
   *
   * @return void
   */
  public function testIfUserCanUpdateACourse()
  {
    $this->withoutExceptionHandling();
    Schema::disableForeignKeyConstraints();
    DB::table((new Course())->getTable())->truncate();
    DB::table((new CourseAcademicDiscipline())->getTable())->truncate();
    $this->artisan('db:seed');
    Schema::enableForeignKeyConstraints();
    $token = file_get_contents(storage_path('test-token.txt'));
    $attendance_types = [
      'Full Time', 'Evening Classes', 'Part Time'
    ];
    $billing_type = [
      'Total Cost', 'Per Term', 'Per Semester', 'Per Module', 'Per Year', 'Per Unit'
    ];
    $course_duration_category_in = [
      'Years', 'Months', 'Weeks', 'Days'
    ];
    $enrollment_details = json_encode([
      'start_month' => 'February',
      'end_month' => 'April',
      'deadline' => '2021-04-18'
    ]);
    $graduate_level = [
      'Examination',
      'Certificate',
      'Diploma',
      'Degree'
    ];
    $institution_code = Factory::create()->randomNumber(5);
    $course_name_slug = Factory::create()->slug(5);
    $course_name = Factory::create()->company;
    $description = Factory::create()->randomHtml(5);
    $course_overview = Factory::create()->randomHtml(5);
    $discipline_code = AcademicDiscipline::take(1)->first()->id;
    $linked_course_categories = call_user_func(function () {
      $list = DB::table((new AcademicDiscipline())->getTable())
        ->take(2)
        ->inRandomOrder()
        ->select(['id'])
        ->get()
        ->toArray();
      return json_encode(array_column($list, 'id'));
    });
    $course_type = CourseType::take(1)->first()->id;
    $institution_course_code = Factory::create()->randomNumber(5);
    $attendance_type = $attendance_types[rand(0, (count($attendance_types) - 1))];
    $learning_mode = LearningMode::take(1)->first()->id;
    $faculty_code = Factory::create()->randomNumber(5);
    $institution_website_course_url = Factory::create()->url;
    $institution_website_application_form_url = Factory::create()->url;
    $course_requirements = Factory::create()->randomHtml(5);
    $standard_fee_billing_type = $billing_type[rand(0, (count($billing_type) - 1))];
    $standard_fee_payable = floatval(number_format(Factory::create()->randomFloat(12, 2), 2));
    $standard_fee_breakdown = Factory::create()->randomHtml(5);
    $foreign_student_fee_payable = floatval(number_format(Factory::create()->randomFloat(12, 2), 2));
    $foreign_student_fee_breakdown = Factory::create()->randomHtml(5);
    $temp_course_image_url = Factory::create()->url;
    $course_structure_breakdown = Factory::create()->randomHtml(5);
    $course_duration = 2.4;
    $course_duration_category = $course_duration_category_in[rand(0, (count($course_duration_category_in) - 1))];
    $graduate_level = $graduate_level[rand(0, (count($graduate_level) - 1))];
    $maximum_scholarship_available = rand(0, 100);
    $accredited_by_acronym = Factory::create()->company;
    $accreditation_organization_url = Factory::create()->url;
    $meta_keywords = Factory::create()->text(50);
    $meta_description = Factory::create()->randomHtml(5);
    $created_at = Carbon::now()->toDateTimeString();
    $payload = [
      'country_code' => 'KE',
      'institution_code' => $institution_code,
      'course_name_slug' => $course_name_slug,
      'course_name' => $course_name,
      'description' => $description,
      'course_overview' => $course_overview,
      'linked_course_categories' => $linked_course_categories,
      'course_type' => $course_type,
      'institution_course_code' => $institution_course_code,
      'attendance_type' => $attendance_type,
      'graduate_level' => $graduate_level,
      'learning_mode' => $learning_mode,
      'faculty_code' => $faculty_code,
      'institution_website_course_url' => $institution_website_course_url,
      'institution_website_application_form_url' => $institution_website_application_form_url,
      'course_requirements' => $course_requirements,
      'content_completeness_score' => 2,
      'enrollment_details' => $enrollment_details,
      'enrollment_in_progress' => 1,
      'standard_fee_billing_type' => $standard_fee_billing_type,
      'currency' => 'USD',
      'standard_fee_payable' => $standard_fee_payable,
      'standard_fee_breakdown' => $standard_fee_breakdown,
      'foreign_student_fee_billing_type' => $billing_type[rand(0, (count($billing_type) - 1))],
      'foreign_student_fee_payable' => $foreign_student_fee_payable,
      'foreign_student_fee_breakdown' => $foreign_student_fee_breakdown,
      'temp_course_image_url' => $temp_course_image_url,
      'course_structure_breakdown' => $course_structure_breakdown,
      'course_duration' => $course_duration,
      'course_duration_category' => $course_duration_category,
      'maximum_scholarship_available' => $maximum_scholarship_available,
      'accredited_by' => $accredited_by_acronym,
      'accredited_by_acronym' => CraydelHelperFunctions::makeAcronym($accredited_by_acronym),
      'accreditation_organization_url' => $accreditation_organization_url,
      'meta_keywords' => $meta_keywords,
      'meta_description' => $meta_description,
      'created_at' => $created_at
    ];
    $course = Course::factory()->count(1)->create();
    $course = $course->first();
    Event::fake();
    $this->post('courses/admin/' . $course->course_code . '/update',
      $payload
      , [
        'token' => $token,
        'locale' => 'en'
      ])
      ->seeJsonContains([
        'status' => true,
        'message' => LanguageTranslationHelper::translate('courses.success.updated')
      ])->seeInDatabase((new Course())->getTable(), [
        'course_code' => $course->course_code,
        'institution_code' => $institution_code,
        'course_name' => $course_name,
        'course_name_slug' => CraydelHelperFunctions::slugifyString($course_name)
      ]);
    $course_id = DB::table((new Course())->getTable())->orderBy('id')->value('id');
    $this->assertCount(
      count(json_decode($linked_course_categories)),
      DB::table((new CourseAcademicDiscipline())->getTable())
        ->where('courses_id', $course_id)
        ->get()
        ->toArray()
    );
    $this->seeInDatabase((new CourseAcademicDiscipline())->getTable(), [
      'courses_id' => $course_id,
      'academic_disciplines_id' => json_decode($linked_course_categories)[0]
    ]);
    Event::assertDispatched(CourseUpdatedEvent::class, function ($event) use ($course) {
      return $event->course_code == $course->course_code;
    });
    Schema::disableForeignKeyConstraints();
    DB::table((new Course())->getTable())->truncate();
    DB::table((new CourseAcademicDiscipline())->getTable())->truncate();
    Schema::enableForeignKeyConstraints();
  }

  /**
   * Test if user can update a course with manual first fee.
   *
   * @return void
   */
  public function testIfUserCanUpdateACourseWithManualFirstYearFees()
  {
    $this->withoutExceptionHandling();
    Schema::disableForeignKeyConstraints();
    DB::table((new Course())->getTable())->truncate();
    DB::table((new CourseAcademicDiscipline())->getTable())->truncate();
    $this->artisan('db:seed');
    Schema::enableForeignKeyConstraints();
    $token = file_get_contents(storage_path('test-token.txt'));
    $attendance_types = [
      'Full Time', 'Evening Classes', 'Part Time'
    ];
    $billing_type = [
      'Total Cost', 'Per Term', 'Per Semester', 'Per Module', 'Per Year', 'Per Unit'
    ];
    $course_duration_category_in = [
      'Years', 'Months', 'Weeks', 'Days'
    ];
    $enrollment_details = json_encode([
      'start_month' => 'February',
      'end_month' => 'April',
      'deadline' => '2021-04-18'
    ]);
    $graduate_level = [
      'Examination',
      'Certificate',
      'Diploma',
      'Degree'
    ];
    $institution_code = Factory::create()->randomNumber(5);
    $course_name_slug = Factory::create()->slug(5);
    $course_name = Factory::create()->company;
    $description = Factory::create()->randomHtml(5);
    $course_overview = Factory::create()->randomHtml(5);
    $discipline_code = AcademicDiscipline::take(1)->first()->id;
    $linked_course_categories = call_user_func(function () {
      $list = DB::table((new AcademicDiscipline())->getTable())
        ->take(2)
        ->inRandomOrder()
        ->select(['id'])
        ->get()
        ->toArray();
      return json_encode(array_column($list, 'id'));
    });
    $course_type = CourseType::take(1)->first()->id;
    $institution_course_code = Factory::create()->randomNumber(5);
    $attendance_type = $attendance_types[rand(0, (count($attendance_types) - 1))];
    $learning_mode = LearningMode::take(1)->first()->id;
    $faculty_code = Factory::create()->randomNumber(5);
    $institution_website_course_url = Factory::create()->url;
    $institution_website_application_form_url = Factory::create()->url;
    $course_requirements = Factory::create()->randomHtml(5);
    $standard_fee_billing_type = $billing_type[rand(0, (count($billing_type) - 1))];
    $standard_fee_payable = floatval(number_format(Factory::create()->randomFloat(12, 2), 2));
    $standard_fee_breakdown = Factory::create()->randomHtml(5);
    $foreign_student_fee_payable = floatval(number_format(Factory::create()->randomFloat(12, 2), 2));
    $foreign_student_fee_breakdown = Factory::create()->randomHtml(5);
    $temp_course_image_url = Factory::create()->url;
    $course_structure_breakdown = Factory::create()->randomHtml(5);
    $course_duration = 2.4;
    $course_duration_category = $course_duration_category_in[rand(0, (count($course_duration_category_in) - 1))];
    $graduate_level = $graduate_level[rand(0, (count($graduate_level) - 1))];
    $maximum_scholarship_available = rand(0, 100);
    $accredited_by_acronym = Factory::create()->company;
    $accreditation_organization_url = Factory::create()->url;
    $meta_keywords = Factory::create()->text(50);
    $meta_description = Factory::create()->randomHtml(5);
    $created_at = Carbon::now()->toDateTimeString();
    $standard_first_year_fee_payable_usd = Factory::create()->randomNumber(5);
    $foreign_student_first_year_fee_payable_usd = Factory::create()->randomNumber(5);
    $ignore_first_year_fees_compute_based_on_total = 1;
    $payload = [
      'country_code' => 'KE',
      'institution_code' => $institution_code,
      'course_name_slug' => $course_name_slug,
      'course_name' => $course_name,
      'description' => $description,
      'course_overview' => $course_overview,
      'linked_course_categories' => $linked_course_categories,
      'course_type' => $course_type,
      'institution_course_code' => $institution_course_code,
      'attendance_type' => $attendance_type,
      'graduate_level' => $graduate_level,
      'learning_mode' => $learning_mode,
      'faculty_code' => $faculty_code,
      'institution_website_course_url' => $institution_website_course_url,
      'institution_website_application_form_url' => $institution_website_application_form_url,
      'course_requirements' => $course_requirements,
      'content_completeness_score' => 2,
      'enrollment_details' => $enrollment_details,
      'enrollment_in_progress' => 1,
      'standard_fee_billing_type' => $standard_fee_billing_type,
      'currency' => 'USD',
      'standard_fee_payable' => $standard_fee_payable,
      'standard_fee_breakdown' => $standard_fee_breakdown,
      'foreign_student_fee_billing_type' => $billing_type[rand(0, (count($billing_type) - 1))],
      'foreign_student_fee_payable' => $foreign_student_fee_payable,
      'foreign_student_fee_breakdown' => $foreign_student_fee_breakdown,
      'temp_course_image_url' => $temp_course_image_url,
      'course_structure_breakdown' => $course_structure_breakdown,
      'course_duration' => $course_duration,
      'course_duration_category' => $course_duration_category,
      'maximum_scholarship_available' => $maximum_scholarship_available,
      'accredited_by' => $accredited_by_acronym,
      'accredited_by_acronym' => CraydelHelperFunctions::makeAcronym($accredited_by_acronym),
      'accreditation_organization_url' => $accreditation_organization_url,
      'meta_keywords' => $meta_keywords,
      'meta_description' => $meta_description,
      'created_at' => $created_at,
      'standard_first_year_fee_payable_usd' => $standard_first_year_fee_payable_usd,
      'foreign_student_first_year_fee_payable_usd' => $foreign_student_first_year_fee_payable_usd,
      'ignore_first_year_fees_compute_based_on_total' => $ignore_first_year_fees_compute_based_on_total
    ];
    $course = Course::factory()->count(1)->create();
    $course = $course->first();
    Event::fake();
    $this->post('courses/admin/' . $course->course_code . '/update',
      $payload
      , [
        'token' => $token,
        'locale' => 'en'
      ])
      ->seeJsonContains([
        'status' => true,
        'message' => LanguageTranslationHelper::translate('courses.success.updated')
      ])->seeInDatabase((new Course())->getTable(), [
        'course_code' => $course->course_code,
        'institution_code' => $institution_code,
        'course_name' => $course_name,
        'course_name_slug' => CraydelHelperFunctions::slugifyString($course_name),
        'standard_first_year_fee_payable_usd' => $standard_first_year_fee_payable_usd,
        'foreign_student_first_year_fee_payable_usd' => $foreign_student_first_year_fee_payable_usd,
        'standard_first_year_fee_payable_usd_is_manual' => 1,
        'foreign_student_first_year_fee_payable_usd_is_manual' => 1,
        'ignore_first_year_fees_compute_based_on_total' => $ignore_first_year_fees_compute_based_on_total
      ]);
    Event::assertDispatched(CourseUpdatedEvent::class, function ($event) use ($course) {
      return $event->course_code == $course->course_code;
    });
    Schema::disableForeignKeyConstraints();
    DB::table((new Course())->getTable())->truncate();
    DB::table((new CourseAcademicDiscipline())->getTable())->truncate();
    Schema::enableForeignKeyConstraints();
  }

  /**
   * Test if user can bulk upload courses
   */
  /*public function testIfUserCanBulkUploadCourses()
  {
      $this->withoutExceptionHandling();
      Schema::disableForeignKeyConstraints();
      DB::table((new Course())->getTable())->truncate();
      DB::table((new CourseBulkImport())->getTable())->truncate();
      Schema::enableForeignKeyConstraints();

      $token = file_get_contents(storage_path('test-token.txt'));

      $fileToUpload = storage_path('test-courses-import.xlsx');
      $fileName = uniqid('institution-list-') . '.xlsx';
      $filePath = sys_get_temp_dir() . '/' . $fileName;
      copy($fileToUpload, $filePath);

      $uploadFile = new UploadedFile($filePath, $fileName, 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', null, true);
      $server = $this->transformHeadersToServerVars(['token' => $token, 'locale' => 'en']);

      $response = $this->call('POST',
          'courses/admin/import',
          [
              'institution_code' => Factory::create()->randomNumber(8),
              'country_code' => 'KE'
          ],
          [
              ['token' => $token,
                  'locale' => 'en']
          ],
          [
              'courses_list' => $uploadFile
          ],
          $server
      );

      $content = json_decode($response->getContent());
      $this->assertObjectHasAttribute('status', $content);
      $this->assertTrue($content->status);
      $this->assertEquals(
          $content->message,
          sprintf(
              LanguageTranslationHelper::translate('courses.success.imported_awaiting_processing'),
              2
          )
      );

      $this->artisan('bulk:retry:courses-upload');
      $this->assertTrue(Course::all()->count() == 2);

      Schema::disableForeignKeyConstraints();
      DB::table((new Course())->getTable())->truncate();
      DB::table((new CourseBulkImport())->getTable())->truncate();
      Schema::enableForeignKeyConstraints();
  }*/

  /**
   * Test if system can push data into the search engine
   * @throws Exception
   */
  public function testIfSystemCanPushDataIntoTheSearchEngine()
  {
    $this->withoutExceptionHandling();
    Schema::disableForeignKeyConstraints();
    DB::table((new Course())->getTable())->truncate();
    DB::table((new CourseAcademicDiscipline())->getTable())->truncate();
    DB::table((new CourseSearchIndexList())->getTable())->truncate();
    $this->artisan('db:seed');
    Schema::enableForeignKeyConstraints();

    $courses = Course::factory()->count(1)->create([
      'is_deleted' => 0,
      'has_updates' => 1,
      'is_active' => 1,
      'is_published' => 1,
      'is_picked_for_indexing' => 0,
      'course_small_image' => 'https://craydel.fra1.cdn.digitaloceanspaces.com/00du9232hnpE9XG6rzjU.jpg',
      'course_image' => 'https://craydel.fra1.cdn.digitaloceanspaces.com/00du9232hnpE9XG6rzjU.jpg'
    ]);

    CourseAcademicDiscipline::factory()->count(2)->create();
    $this->artisan('search:course:generate-index-list');

    (new PushCourseDataToSearchEngineCommandController(
      $courses->first()->course_code
    ))->make()->push();

    $this->seeInDatabase((new CourseSearchIndexList())->getTable(), [
      'course_code' => $courses->first()->course_code,
      'is_published' => 1,
      'is_active' => 1,
      'has_updates' => 0,
      'is_picked_for_indexing' => 0,
      'is_picked_for_unpublishing' => 0
    ]);

    (new PushCourseDataToSearchEngineCommandController(
      $courses->first()->course_code
    ))->make()->delete();

    Schema::disableForeignKeyConstraints();
    DB::table((new Course())->getTable())->truncate();
    DB::table((new CourseAcademicDiscipline())->getTable())->truncate();
    DB::table((new CourseSearchIndexList())->getTable())->truncate();
    Schema::enableForeignKeyConstraints();
  }

  /**
   * Test if system can push data into the search engine
   * @throws Exception
   */
  public function testIfSystemCanUnpublishTheCourseSearchEngine()
  {
    $this->withoutExceptionHandling();
    Schema::disableForeignKeyConstraints();
    DB::table((new Course())->getTable())->truncate();
    DB::table((new CourseAcademicDiscipline())->getTable())->truncate();
    DB::table((new CourseSearchIndexList())->getTable())->truncate();
    $this->artisan('db:seed');
    Schema::enableForeignKeyConstraints();
    $course = Course::factory()->count(1)->create([
      'is_deleted' => 0,
      'has_updates' => 1,
      'is_active' => 1,
      'is_published' => 1,
      'is_picked_for_indexing' => 0,
      'course_small_image' => 'https://craydel.fra1.cdn.digitaloceanspaces.com/00du9232hnpE9XG6rzjU.jpg',
      'course_image' => 'https://craydel.fra1.cdn.digitaloceanspaces.com/00du9232hnpE9XG6rzjU.jpg'
    ]);
    CourseAcademicDiscipline::factory()->count(2)->create();
    $this->artisan('search:course:generate-index-list');
    (new PushCourseDataToSearchEngineCommandController(
      $course->first()->course_code
    ))->make()->push();

    (new PushCourseDataToSearchEngineCommandController(
      $course->first()->course_code
    ))->make()->delete();
    $this->seeInDatabase((new CourseSearchIndexList())->getTable(), [
      'course_code' => $course->first()->course_code,
      'is_active' => 0,
      'is_published' => 0,
      'should_unpublish' => 0,
      'is_picked_for_unpublishing' => 0,
    ]);
    Schema::disableForeignKeyConstraints();
    DB::table((new Course())->getTable())->truncate();
    DB::table((new CourseAcademicDiscipline())->getTable())->truncate();
    DB::table((new CourseSearchIndexList())->getTable())->truncate();
    Schema::enableForeignKeyConstraints();
  }

  /**
   * Test if user can get a single course details
   */
  public function testIfUserCanGetSingleCourseDetails()
  {
    $this->withoutExceptionHandling();
    Schema::disableForeignKeyConstraints();
    DB::table((new Course())->getTable())->truncate();
    DB::table((new CourseAcademicDiscipline())->getTable())->truncate();
    $this->artisan('db:seed');
    Schema::enableForeignKeyConstraints();
    $course_code = 'CO2CM6B2SBP';
    $institution_code = 'INTANHXXFAYVR';
    $token = file_get_contents(storage_path('test-token.txt'));
    Course::factory()->count(1)->create([
      'course_code' => $course_code,
      'institution_code' => $institution_code,
    ]);
    $response = $this->get('courses/' . $course_code, [
      'locale' => 'en',
      'token' => $token,
    ])->seeJsonContains([
      'status' => true,
      'message' => LanguageTranslationHelper::translate('courses.success.details_shown')
    ])->seeJsonStructure([
      'data' => [
        'course_details' => [
          'country_code',
          'institution_code',
          'course_name',
          'course_rating',
          'discipline',
          'learning_mode',
          'linked_disciplines',
          'description_plain_text',
          'linked_blog_articles'
        ],
        'similar_courses'
      ]
    ]);
    $this->assertJson($response->response->getContent());
    $course = json_decode($response->response->getContent());
    $this->assertObjectHasAttribute('data', $course);
    $this->assertObjectHasAttribute('course_details', $course->data);
    $this->assertObjectHasAttribute('enrollment_details', $course->data->course_details);
    $this->assertObjectHasAttribute('institution_summary', $course->data->course_details);
    $this->assertJson($course->data->course_details->enrollment_details);
    $this->assertNotNull($course->data->course_details->enrollment_details);
    Schema::disableForeignKeyConstraints();
    DB::table((new Course())->getTable())->truncate();
    DB::table((new CourseAcademicDiscipline())->getTable())->truncate();
    Schema::enableForeignKeyConstraints();
  }

  /**
   * System can convert the standard fee payable USD value
   */
  public function testIfSystemCanConvertTheStandardFeesToUSD()
  {
    $amount = rand(1, 100);
    $base_currencies = ['KES', 'UGX', 'TZS', 'ZAR', 'eur'];
    $convert_to = ['USD', 'KES', 'EUR'];
    $convert = new ForexController(
      $base_currencies[rand(0, (count($base_currencies) - 1))],
      $convert_to[rand(0, (count($convert_to) - 1))],
      $amount
    );
    $result = $convert->convert();
    $this->assertTrue($result->status);
    $this->assertTrue(!empty($result->data->converted_value));
    $this->assertTrue(!empty($result->data->amount));
  }

  /**
   * Test if user can un-feature a course
   */
  public function testIfUserCanFeatureOrUn_featureACourse()
  {
    $this->withoutExceptionHandling();
    Schema::disableForeignKeyConstraints();
    DB::table((new Course())->getTable())->truncate();
    DB::table((new CourseAcademicDiscipline())->getTable())->truncate();
    $this->artisan('db:seed');
    Schema::enableForeignKeyConstraints();
    $course_code = Str::random();
    $institution_code = Str::random();
    Course::factory()->count(1)->create([
      'course_code' => $course_code,
      'institution_code' => $institution_code,
      'is_featured' => 1
    ]);
    $token = file_get_contents(storage_path('test-token.txt'));
    $this->post('courses/admin/' . $course_code . '/feature',
      []
      , [
        'token' => $token,
        'locale' => 'en'
      ])
      ->seeJsonContains([
        'status' => true,
        'message' => LanguageTranslationHelper::translate('courses.success.un_featured')
      ]);
    Schema::disableForeignKeyConstraints();
    DB::table((new Course())->getTable())->truncate();
    DB::table((new CourseAcademicDiscipline())->getTable())->truncate();
    Schema::enableForeignKeyConstraints();
  }

  /**
   * Test if user can un-feature the a course
   */
  public function testIfUserCanFeatureOrFeatureACourse()
  {
    $this->withoutExceptionHandling();
    Schema::disableForeignKeyConstraints();
    DB::table((new Course())->getTable())->truncate();
    DB::table((new CourseAcademicDiscipline())->getTable())->truncate();
    $this->artisan('db:seed');
    Schema::enableForeignKeyConstraints();
    $course_code = Str::random();
    $institution_code = Str::random();
    Course::factory()->count(1)->create([
      'course_code' => $course_code,
      'institution_code' => $institution_code,
      'is_featured' => 0
    ]);
    $token = file_get_contents(storage_path('test-token.txt'));
    $this->post('courses/admin/' . $course_code . '/feature',
      []
      , [
        'token' => $token,
        'locale' => 'en'
      ])
      ->seeJsonContains([
        'status' => true,
        'message' => LanguageTranslationHelper::translate('courses.success.featured')
      ]);
    Schema::disableForeignKeyConstraints();
    DB::table((new Course())->getTable())->truncate();
    DB::table((new CourseAcademicDiscipline())->getTable())->truncate();
    Schema::enableForeignKeyConstraints();
  }

  /**
   * Test that user can get the latest USD exchange rate
   */
  public function testIfUserCanGetLatestUSDExchangeRate()
  {
    $response = $this->get('courses/usd-exchange-rate', [
      'locale' => 'en'
    ])->seeJsonContains([
      'status' => true,
      'message' => 'Latest'
    ])->seeJsonStructure([
      'data' => [
        'rates'
      ]
    ]);
    $results = json_decode($response->response->getContent());
    $this->assertIsObject($results->data);
    $this->assertIsObject($results->data->rates);
  }

  /**
   * Test that user can get country specific footer menu
   */
  public function testIfUserCanGetCountrySpecificFooterMenu()
  {
    $this->withoutExceptionHandling();
    Schema::disableForeignKeyConstraints();
    DB::table((new Course())->getTable())->truncate();
    DB::table((new Leads())->getTable())->truncate();
    DB::table((new CourseAcademicDiscipline())->getTable())->truncate();
    $this->artisan('db:seed');
    Schema::enableForeignKeyConstraints();
    Course::factory()->count(50)->create();
    $this->artisan('cache:clear');
    $this->artisan('bulk:courses:create-footer');
    $country_name = 'Kenya';
    $response = $this->post('courses/footer-menu', [
      'country' => $country_name
    ], [
      'locale' => 'en'
    ])->seeJsonContains([
      'status' => true,
      'message' => 'Listed'
    ])->seeJsonStructure([
      'data'
    ]);
    $response = $response->response->getContent();
    $this->assertJson($response);
    $response = json_decode($response);
    $this->assertIsObject($response);
    $this->assertTrue(isset($response->data));
    $this->assertIsArray($response->data);
    $this->assertTrue(isset($response->data[0]) && !is_null($response->data[0]));
    $this->assertIsObject($response->data[0]);
    $this->assertObjectHasAttribute('name', $response->data[0]);
    $this->assertObjectHasAttribute('slug', $response->data[0]);
    $this->assertTrue(!empty($response->data[0]->name));
    $this->assertTrue(!empty($response->data[0]->slug));
    $this->assertEquals(
      sprintf(
        CreateCoursesFooterCommandController::$country_route_format,
        CraydelHelperFunctions::hyphenatedSlug($response->data[0]->name),
        CraydelHelperFunctions::hyphenatedSlug($country_name)
      ),
      $response->data[0]->slug
    );
    $this->artisan('cache:clear');
    Schema::disableForeignKeyConstraints();
    DB::table((new Course())->getTable())->truncate();
    DB::table((new Leads())->getTable())->truncate();
    DB::table((new CourseAcademicDiscipline())->getTable())->truncate();
    Schema::enableForeignKeyConstraints();
  }

  /**
   * Test that the system can generate popularity score
   */
  public function testIfSystemCanGeneratePopularityScore()
  {
    $this->withoutExceptionHandling();
    Schema::disableForeignKeyConstraints();
    DB::table((new Course())->getTable())->truncate();
    DB::table((new CourseAcademicDiscipline())->getTable())->truncate();
    $this->artisan('db:seed');
    Schema::enableForeignKeyConstraints();
    Course::factory()->count(10)->create([
      'popularity' => null,
      'is_active' => 1
    ]);
    $this->artisan('support:generate-popularity-index');
    $course = Course::all()->first();
    $this->assertNotNull($course->popularity);
    $this->assertIsFloat($course->popularity);
    // dd($course);
    $this->seeInDatabase((new Course())->getTable(), [
//            'popularity' => $course->popularity,
      'has_updates' => 1,
      'is_picked_for_indexing' => 0,
      'time_picked_for_indexing' => null
    ]);
    Schema::disableForeignKeyConstraints();
    DB::table((new Course())->getTable())->truncate();
    DB::table((new CourseAcademicDiscipline())->getTable())->truncate();
    Schema::enableForeignKeyConstraints();
  }

  /**
   * Test if the system will update the first fees if the ignore_first_year_fees_compute_based_on_total flag is set to 1
   */
  public function testIfSystemWillNotUpdateTheFirstYearFeesIfCourseIsSetToIgnoreFirstYearFeesComputeBasedOnTotalIsTrue()
  {
    $this->withoutExceptionHandling();
    Schema::disableForeignKeyConstraints();
    DB::table((new Course())->getTable())->truncate();
    DB::table((new CourseAcademicDiscipline())->getTable())->truncate();
    $this->artisan('db:seed');
    Schema::enableForeignKeyConstraints();
    $token = file_get_contents(storage_path('test-token.txt'));
    $attendance_types = [
      'Full Time', 'Evening Classes', 'Part Time'
    ];
    $billing_type = [
      'Total Cost', 'Per Term', 'Per Semester', 'Per Module', 'Per Year', 'Per Unit'
    ];
    $course_duration_category_in = [
      'Years', 'Months', 'Weeks', 'Days'
    ];
    $enrollment_details = json_encode([
      'start_month' => 'February',
      'end_month' => 'April',
      'deadline' => '2021-04-18'
    ]);
    $graduate_level = [
      'Examination',
      'Certificate',
      'Diploma',
      'Degree'
    ];
    $institution_code = Factory::create()->randomNumber(5);
    $course_name_slug = Factory::create()->slug(5);
    $course_name = Factory::create()->company;
    $description = Factory::create()->randomHtml(5);
    $course_overview = Factory::create()->randomHtml(5);
    $discipline_code = AcademicDiscipline::take(1)->first()->id;
    $linked_course_categories = call_user_func(function () {
      $list = DB::table((new AcademicDiscipline())->getTable())
        ->take(2)
        ->inRandomOrder()
        ->select(['id'])
        ->get()
        ->toArray();
      return json_encode(array_column($list, 'id'));
    });
    $course_type = CourseType::take(1)->first()->id;
    $institution_course_code = Factory::create()->randomNumber(5);
    $attendance_type = $attendance_types[rand(0, (count($attendance_types) - 1))];
    $learning_mode = LearningMode::take(1)->first()->id;
    $faculty_code = Factory::create()->randomNumber(5);
    $institution_website_course_url = Factory::create()->url;
    $institution_website_application_form_url = Factory::create()->url;
    $course_requirements = Factory::create()->randomHtml(5);
    $standard_fee_billing_type = $billing_type[rand(0, (count($billing_type) - 1))];
    $standard_fee_payable = floatval(number_format(Factory::create()->randomFloat(12, 2), 2));
    $standard_fee_breakdown = Factory::create()->randomHtml(5);
    $foreign_student_fee_payable = floatval(number_format(Factory::create()->randomFloat(12, 2), 2));
    $foreign_student_fee_breakdown = Factory::create()->randomHtml(5);
    $temp_course_image_url = Factory::create()->url;
    $course_structure_breakdown = Factory::create()->randomHtml(5);
    $course_duration = 2.4;
    $course_duration_category = $course_duration_category_in[rand(0, (count($course_duration_category_in) - 1))];
    $graduate_level = $graduate_level[rand(0, (count($graduate_level) - 1))];
    $maximum_scholarship_available = rand(0, 100);
    $accredited_by_acronym = Factory::create()->company;
    $accreditation_organization_url = Factory::create()->url;
    $meta_keywords = Factory::create()->text(50);
    $meta_description = Factory::create()->randomHtml(5);
    $created_at = Carbon::now()->toDateTimeString();
    $standard_fee_payable_usd = Factory::create()->randomNumber(3);
    $foreign_student_fee_payable_usd = Factory::create()->randomNumber(3);
    $standard_first_year_fee_payable_usd = Factory::create()->randomNumber(4);
    $foreign_student_first_year_fee_payable_usd = Factory::create()->randomNumber(4);
    $course = Course::factory()->count(1)->create();
    $course = $course->first();
    $currency = 'KES';
    $payload = [
      'country_code' => 'KE',
      'institution_code' => $institution_code,
      'course_name_slug' => $course_name_slug,
      'course_name' => $course_name,
      'description' => $description,
      'course_overview' => $course_overview,
      'linked_course_categories' => $linked_course_categories,
      'course_type' => $course_type,
      'institution_course_code' => $institution_course_code,
      'attendance_type' => $attendance_type,
      'graduate_level' => $graduate_level,
      'learning_mode' => $learning_mode,
      'faculty_code' => $faculty_code,
      'institution_website_course_url' => $institution_website_course_url,
      'institution_website_application_form_url' => $institution_website_application_form_url,
      'course_requirements' => $course_requirements,
      'content_completeness_score' => 2,
      'enrollment_details' => $enrollment_details,
      'enrollment_in_progress' => 1,
      'standard_fee_billing_type' => $standard_fee_billing_type,
      'currency' => $currency,
      'standard_fee_payable' => $standard_fee_payable,
      'standard_fee_breakdown' => $standard_fee_breakdown,
      'foreign_student_fee_billing_type' => $billing_type[rand(0, (count($billing_type) - 1))],
      'foreign_student_fee_payable' => $foreign_student_fee_payable,
      'foreign_student_fee_breakdown' => $foreign_student_fee_breakdown,
      'temp_course_image_url' => $temp_course_image_url,
      'course_structure_breakdown' => $course_structure_breakdown,
      'course_duration' => $course_duration,
      'course_duration_category' => $course_duration_category,
      'maximum_scholarship_available' => $maximum_scholarship_available,
      'accredited_by' => $accredited_by_acronym,
      'accredited_by_acronym' => CraydelHelperFunctions::makeAcronym($accredited_by_acronym),
      'accreditation_organization_url' => $accreditation_organization_url,
      'meta_keywords' => $meta_keywords,
      'meta_description' => $meta_description,
      'created_at' => $created_at,
      'standard_fee_payable_usd' => $standard_fee_payable_usd,
      'standard_first_year_fee_payable_usd' => $standard_first_year_fee_payable_usd,
      'foreign_student_fee_payable_usd' => $foreign_student_fee_payable_usd,
      'foreign_student_first_year_fee_payable_usd' => $foreign_student_first_year_fee_payable_usd,
      'ignore_first_year_fees_compute_based_on_total' => 1
    ];
    $course = $course->first();
    $this->post('courses/admin/' . $course->course_code . '/update',
      $payload
      , [
        'token' => $token,
        'locale' => 'en'
      ])
      ->seeJsonContains([
        'status' => true,
        'message' => LanguageTranslationHelper::translate('courses.success.updated')
      ])->seeInDatabase((new Course())->getTable(), [
        'course_code' => $course->course_code,
        'institution_code' => $institution_code,
        'course_name' => $course_name,
        'course_name_slug' => CraydelHelperFunctions::slugifyString($course_name)
      ]);
    CourseController::updateCourseStandardFeePayableUSD(
      $course->course_code,
      $currency,
      $standard_fee_payable
    );
    $this->seeInDatabase((new Course())->getTable(), [
      'course_code' => $course->course_code,
      'standard_first_year_fee_payable_usd' => $standard_first_year_fee_payable_usd,
      'foreign_student_first_year_fee_payable_usd' => $foreign_student_first_year_fee_payable_usd
    ]);
    Schema::disableForeignKeyConstraints();
    DB::table((new Course())->getTable())->truncate();
    DB::table((new CourseAcademicDiscipline())->getTable())->truncate();
    Schema::enableForeignKeyConstraints();
  }

  /**
   * Test if the system will updates the first fees if the ignore_first_year_fees_compute_based_on_total flag is set to 0
   */
  public function testIfSystemWillUpdateTheFirstYearFeesIfCourseIsSetToIgnoreFirstYearFeesComputeBasedOnTotalIsFalse()
  {
    $this->withoutExceptionHandling();
    Schema::disableForeignKeyConstraints();
    DB::table((new Course())->getTable())->truncate();
    DB::table((new CourseAcademicDiscipline())->getTable())->truncate();
    $this->artisan('db:seed');
    Schema::enableForeignKeyConstraints();
    $token = file_get_contents(storage_path('test-token.txt'));
    $attendance_types = [
      'Full Time', 'Evening Classes', 'Part Time'
    ];
    $billing_type = [
      'Total Cost', 'Per Term', 'Per Semester', 'Per Module', 'Per Year', 'Per Unit'
    ];
    $course_duration_category_in = [
      'Years', 'Months', 'Weeks', 'Days'
    ];
    $enrollment_details = json_encode([
      'start_month' => 'February',
      'end_month' => 'April',
      'deadline' => '2021-04-18'
    ]);
    $graduate_level = [
      'Examination',
      'Certificate',
      'Diploma',
      'Degree'
    ];
    $institution_code = Factory::create()->randomNumber(5);
    $course_name_slug = Factory::create()->slug(5);
    $course_name = Factory::create()->company;
    $description = Factory::create()->randomHtml(5);
    $course_overview = Factory::create()->randomHtml(5);
    $discipline_code = AcademicDiscipline::take(1)->first()->id;
    $linked_course_categories = call_user_func(function () {
      $list = DB::table((new AcademicDiscipline())->getTable())
        ->take(2)
        ->inRandomOrder()
        ->select(['id'])
        ->get()
        ->toArray();
      return json_encode(array_column($list, 'id'));
    });
    $course_type = CourseType::take(1)->first()->id;
    $institution_course_code = Factory::create()->randomNumber(5);
    $attendance_type = $attendance_types[rand(0, (count($attendance_types) - 1))];
    $learning_mode = LearningMode::take(1)->first()->id;
    $faculty_code = Factory::create()->randomNumber(5);
    $institution_website_course_url = Factory::create()->url;
    $institution_website_application_form_url = Factory::create()->url;
    $course_requirements = Factory::create()->randomHtml(5);
    $standard_fee_billing_type = $billing_type[rand(0, (count($billing_type) - 1))];
    $standard_fee_payable = floatval(number_format(Factory::create()->randomFloat(12, 2), 2));
    $standard_fee_breakdown = Factory::create()->randomHtml(5);
    $foreign_student_fee_payable = floatval(number_format(Factory::create()->randomFloat(12, 2), 2));
    $foreign_student_fee_breakdown = Factory::create()->randomHtml(5);
    $temp_course_image_url = Factory::create()->url;
    $course_structure_breakdown = Factory::create()->randomHtml(5);
    $course_duration = 2.4;
    $course_duration_category = $course_duration_category_in[rand(0, (count($course_duration_category_in) - 1))];
    $graduate_level = $graduate_level[rand(0, (count($graduate_level) - 1))];
    $maximum_scholarship_available = rand(0, 100);
    $accredited_by_acronym = Factory::create()->company;
    $accreditation_organization_url = Factory::create()->url;
    $meta_keywords = Factory::create()->text(50);
    $meta_description = Factory::create()->randomHtml(5);
    $created_at = Carbon::now()->toDateTimeString();
    $standard_fee_payable_usd = Factory::create()->randomNumber(3);
    $foreign_student_fee_payable_usd = Factory::create()->randomNumber(3);
    $standard_first_year_fee_payable_usd = Factory::create()->randomNumber(4);
    $foreign_student_first_year_fee_payable_usd = Factory::create()->randomNumber(4);
    $course = Course::factory()->count(1)->create();
    $course = $course->first();
    $currency = 'KES';
    $payload = [
      'country_code' => 'KE',
      'institution_code' => $institution_code,
      'course_name_slug' => $course_name_slug,
      'course_name' => $course_name,
      'description' => $description,
      'course_overview' => $course_overview,
      'linked_course_categories' => $linked_course_categories,
      'course_type' => $course_type,
      'institution_course_code' => $institution_course_code,
      'attendance_type' => $attendance_type,
      'graduate_level' => $graduate_level,
      'learning_mode' => $learning_mode,
      'faculty_code' => $faculty_code,
      'institution_website_course_url' => $institution_website_course_url,
      'institution_website_application_form_url' => $institution_website_application_form_url,
      'course_requirements' => $course_requirements,
      'content_completeness_score' => 2,
      'enrollment_details' => $enrollment_details,
      'enrollment_in_progress' => 1,
      'standard_fee_billing_type' => $standard_fee_billing_type,
      'currency' => $currency,
      'standard_fee_payable' => $standard_fee_payable,
      'standard_fee_breakdown' => $standard_fee_breakdown,
      'foreign_student_fee_billing_type' => $billing_type[rand(0, (count($billing_type) - 1))],
      'foreign_student_fee_payable' => $foreign_student_fee_payable,
      'foreign_student_fee_breakdown' => $foreign_student_fee_breakdown,
      'temp_course_image_url' => $temp_course_image_url,
      'course_structure_breakdown' => $course_structure_breakdown,
      'course_duration' => $course_duration,
      'course_duration_category' => $course_duration_category,
      'maximum_scholarship_available' => $maximum_scholarship_available,
      'accredited_by' => $accredited_by_acronym,
      'accredited_by_acronym' => CraydelHelperFunctions::makeAcronym($accredited_by_acronym),
      'accreditation_organization_url' => $accreditation_organization_url,
      'meta_keywords' => $meta_keywords,
      'meta_description' => $meta_description,
      'created_at' => $created_at,
      'standard_fee_payable_usd' => $standard_fee_payable_usd,
      'standard_first_year_fee_payable_usd' => $standard_first_year_fee_payable_usd,
      'foreign_student_fee_payable_usd' => $foreign_student_fee_payable_usd,
      'foreign_student_first_year_fee_payable_usd' => $foreign_student_first_year_fee_payable_usd,
      'ignore_first_year_fees_compute_based_on_total' => 0
    ];
    $course = $course->first();
    $this->post('courses/admin/' . $course->course_code . '/update',
      $payload
      , [
        'token' => $token,
        'locale' => 'en'
      ])
      ->seeJsonContains([
        'status' => true,
        'message' => LanguageTranslationHelper::translate('courses.success.updated')
      ])->seeInDatabase((new Course())->getTable(), [
        'course_code' => $course->course_code,
        'institution_code' => $institution_code,
        'course_name' => $course_name,
        'course_name_slug' => CraydelHelperFunctions::slugifyString($course_name)
      ]);
    CourseController::updateCourseStandardFeePayableUSD(
      $course->course_code,
      $currency,
      $standard_fee_payable
    );
    $convert = new ForexController(
      $currency,
      'USD',
      $standard_fee_payable
    );
    $result = $convert->convert();
    $standard_first_year_fee_payable = CalculateFirstYearFeeCommandController::calculate(
      DB::table((new Course())->getTable())->where('course_code', $course->course_code)->first(),
      $result->data->converted_value
    );
    $convert = new ForexController(
      $currency,
      'USD',
      $foreign_student_fee_payable
    );
    $result = $convert->convert();
    $foreign_student_first_year_fee_payable = CalculateFirstYearFeeCommandController::calculate(
      DB::table((new Course())->getTable())->where('course_code', $course->course_code)->first(),
      $result->data->converted_value
    );
    $this->seeInDatabase((new Course())->getTable(), [
      'course_code' => $course->course_code,
      'standard_first_year_fee_payable_usd' => $standard_first_year_fee_payable,
      'foreign_student_first_year_fee_payable_usd' => $foreign_student_first_year_fee_payable
    ]);
    Schema::disableForeignKeyConstraints();
    DB::table((new Course())->getTable())->truncate();
    DB::table((new CourseAcademicDiscipline())->getTable())->truncate();
    Schema::enableForeignKeyConstraints();
  }

  /**
   * Test if user can fetch the top applied for courses based on number of leads
   */
  public function testIfUserCanFetchTheTopAppliedForCoursesBasedOnNumberOfLeads()
  {
    $this->withoutExceptionHandling();
    Schema::disableForeignKeyConstraints();
    DB::table((new Course())->getTable())->truncate();
    DB::table((new CourseAcademicDiscipline())->getTable())->truncate();
    $this->artisan('db:seed');
    Schema::enableForeignKeyConstraints();
    Course::factory()->count(10)->create([
      'institution_code' => 'x9qkVi0ZHA',
      'course_type' => Factory::create()->randomElement([1, 2]),
      'is_active' => 1,
      'is_deleted' => 0
    ]);
    DB::table((new Course())->getTable())
      ->update([
        'course_type' => DB::raw('FLOOR( 1 + RAND( ) * 2 )')
      ]);
    $this->get('courses/get-top-courses', [
      'locale' => 'en'
    ])->seeJsonContains([
      'status' => true,
      'message' => 'Listed'
    ])->seeJsonStructure([
      'data' => [
        'undergraduate' => [
          'courses' => [
            '*' => [
              'course_code',
              'course_small_image',
              'course_image',
              'institution_name',
              'total_leads_submitted_to_crm',
              'discipline',
              'url_course_slug',
              'course_name',
              'institution_country'
            ]
          ],
          'total_leads_submitted_to_crm'
        ],
        'postgraduate' => [
          'courses' => [
            '*' => [
              'course_code',
              'course_small_image',
              'course_image',
              'institution_name',
              'total_leads_submitted_to_crm',
              'discipline',
              'url_course_slug',
              'course_name',
              'institution_country'
            ]
          ],
          'total_leads_submitted_to_crm'
        ]
      ]
    ]);
  }

  /**
   * Test if the get institution course category RPC returns the distinct academic disciplines
   */
  public function testIfInstitutionCourseCategoryRPCReturnsDistinctAcademicDisciplines()
  {
    $this->withoutExceptionHandling();
    Schema::disableForeignKeyConstraints();
    DB::table((new Course())->getTable())->truncate();
    DB::table((new CourseAcademicDiscipline())->getTable())->truncate();
    $this->artisan('db:seed');
    Schema::enableForeignKeyConstraints();
    Course::factory()->count(10)->create([
      'institution_code' => 'x9qkVi0ZHA',
      'course_type' => Factory::create()->randomElement([1, 2]),
      'is_active' => 1,
      'is_deleted' => 0
    ]);
    $this->get('courses/rpc/get-institution-course-categories/x9qkVi0ZHA', [
      'locale' => 'en'
    ])->seeJsonContains([
      'status' => true,
      'message' => 'Listed'
    ])->seeJsonStructure([
      'data' => [
        'academic_disciplines'
      ]
    ]);
  }

  /**
   * Test if the course search index list is generated
   */
  public function testIfTheCourseSearchIndexListIsGenerated()
  {
    $this->withoutExceptionHandling();
    Schema::disableForeignKeyConstraints();
    DB::table((new Course())->getTable())->truncate();
    DB::table((new CourseAcademicDiscipline())->getTable())->truncate();
    $this->artisan('db:seed');
    Schema::enableForeignKeyConstraints();
    $courses = Course::factory()->count(1)->create([
      'is_deleted' => 0,
      'has_updates' => 1,
      'is_active' => 1,
      'is_published' => 1,
      'is_picked_for_indexing' => 0,
      'course_small_image' => 'https://craydel.fra1.cdn.digitaloceanspaces.com/00du9232hnpE9XG6rzjU.jpg',
      'course_image' => 'https://craydel.fra1.cdn.digitaloceanspaces.com/00du9232hnpE9XG6rzjU.jpg'
    ]);
    CourseAcademicDiscipline::factory()->count(2)->create();
    $this->artisan('search:course:generate-index-list');
    $indexed_courses = DB::table((new CourseSearchIndexList())->getTable())
      ->where('course_code', $courses->first()->course_code)
      ->first();
    $this->assertCount(
      2,
      DB::table((new CourseSearchIndexList())->getTable())->where('course_code', $courses->first()->course_code)->get()
    );
    $this->assertTrue($indexed_courses->has_updates === 1);
    $this->assertTrue($indexed_courses->is_active === 1);
    $this->assertTrue($indexed_courses->is_published === 1);
    $courses = Course::all();
    $this->assertTrue($courses->first()->has_updates == 0);
    $this->assertTrue($courses->first()->is_picked_for_indexing == 0);
    Schema::disableForeignKeyConstraints();
    DB::table((new Course())->getTable())->truncate();
    DB::table((new CourseAcademicDiscipline())->getTable())->truncate();
    Schema::enableForeignKeyConstraints();
  }

  /**
   * Test that the course copies are only updated and not deleted or duplicated if the course copy(s) already exists
   */
  public function testThatTheCourseCopiesAreOnlyUpdatedAndNotDeletedOrDuplicatedIfTheCourseCopyAlreadyExists()
  {
    $this->withoutExceptionHandling();
    Schema::disableForeignKeyConstraints();
    DB::table((new Course())->getTable())->truncate();
    DB::table((new CourseAcademicDiscipline())->getTable())->truncate();
    $this->artisan('db:seed');
    Schema::enableForeignKeyConstraints();
    $courses = Course::factory()->count(1)->create([
      'is_deleted' => 0,
      'has_updates' => 1,
      'is_active' => 1,
      'is_published' => 1,
      'is_picked_for_indexing' => 0,
      'course_small_image' => 'https://craydel.fra1.cdn.digitaloceanspaces.com/00du9232hnpE9XG6rzjU.jpg',
      'course_image' => 'https://craydel.fra1.cdn.digitaloceanspaces.com/00du9232hnpE9XG6rzjU.jpg'
    ]);
    $course_discipline = CourseAcademicDiscipline::factory()->create([
      'courses_id' => $courses->first()->id,
      'academic_disciplines_id' => 1
    ]);
    $this->artisan('search:course:generate-index-list');
    $this->artisan('search:course:generate-index-list');
    $this->assertCount(
      1,
      DB::table((new CourseSearchIndexList())->getTable())->where('course_code', $courses->first()->course_code)->get()
    );
    $index_course = DB::table((new CourseSearchIndexList())->getTable())
      ->where('course_code', $courses->first()->course_code)
      ->first();
    $this->assertTrue(
      $index_course->indexing_object_id == $courses->first()->course_code . $course_discipline->first()->academic_disciplines_id
    );
    Course::where('course_code', $courses->first()->course_code)
      ->update([
        'course_name' => 'New course name',
        'has_updates' => 1
      ]);
    $this->artisan('search:course:generate-index-list');
    $index_course = DB::table((new CourseSearchIndexList())->getTable())
      ->where('course_code', $courses->first()->course_code)
      ->first();
    $this->assertTrue($index_course->course_name == 'New course name');
    Schema::disableForeignKeyConstraints();
    DB::table((new Course())->getTable())->truncate();
    DB::table((new CourseAcademicDiscipline())->getTable())->truncate();
    Schema::enableForeignKeyConstraints();
  }

  /**
   * Test if previously updated course can be mapped inti the course academic category discipline table
   */
  public function testIfPreviouslyUpdatedCourseCanBeMappedIntoTheCourseAcademicCategoryDisciplineTable()
  {
    $this->withoutExceptionHandling();
    Schema::disableForeignKeyConstraints();
    DB::table((new Course())->getTable())->truncate();
    DB::table((new CourseAcademicDiscipline())->getTable())->truncate();
    $this->artisan('db:seed');
    Schema::enableForeignKeyConstraints();
    Course::factory()->count(10)->create();
    $this->artisan('bulk:courses:generate-academic-category-linkage-from-existing-courses');
    $this->assertCount(
      10,
      DB::table((new Course())->getTable())->where('course_academic_category_is_generated', 1)->get()
    );
    $this->assertCount(
      10,
      DB::table((new CourseAcademicDiscipline())->getTable())->get()
    );
    Schema::disableForeignKeyConstraints();
    DB::table((new Course())->getTable())->truncate();
    DB::table((new CourseAcademicDiscipline())->getTable())->truncate();
    Schema::enableForeignKeyConstraints();
  }

  public function testIfCoursesKeyPhrasesAreExtracted()
  {
    $this->withoutExceptionHandling();
    Schema::disableForeignKeyConstraints();
    DB::table((new Course())->getTable())->truncate();
    DB::table((new CourseAcademicDiscipline())->getTable())->truncate();
    DB::table((new CourseSearchIndexList())->getTable())->truncate();
    $this->artisan('db:seed');
    Schema::enableForeignKeyConstraints();

    $courses = Course::factory()->count(1)->create([
      'is_deleted' => 0,
      'has_updates' => 1,
      'is_active' => 1,
      'is_published' => 1,
      'is_picked_for_indexing' => 0,
      'course_small_image' => 'https://craydel.fra1.cdn.digitaloceanspaces.com/00du9232hnpE9XG6rzjU.jpg',
      'course_image' => 'https://craydel.fra1.cdn.digitaloceanspaces.com/00du9232hnpE9XG6rzjU.jpg'
    ]);

    CourseAcademicDiscipline::factory()->count(2)->create();
    $this->artisan('search:course:generate-index-list');
    $this->artisan('bulk:process-course-overview-to-key-phrases');

    $this->seeInDatabase((new CourseSearchIndexList())->getTable(), [
      'course_code' => $courses->first()->course_code,
      'is_picked_for_phrases_selection' => 1,
    ]);
    Schema::disableForeignKeyConstraints();
    DB::table((new Course())->getTable())->truncate();
    DB::table((new CourseAcademicDiscipline())->getTable())->truncate();
    DB::table((new CourseSearchIndexList())->getTable())->truncate();
    Schema::enableForeignKeyConstraints();
  }

  /**
   * @throws Exception
   */
  public function testIfSystemCanPushDataCanBeIndexedInternalSearchEngine()
  {
    $this->withoutExceptionHandling();
    Schema::disableForeignKeyConstraints();
    DB::table((new Course())->getTable())->truncate();
    DB::table((new CourseAcademicDiscipline())->getTable())->truncate();
    DB::table((new CourseSearchIndexList())->getTable())->truncate();
    $this->artisan('db:seed');
    Schema::enableForeignKeyConstraints();

    $courses = Course::factory()->count(1)->create([
      'is_deleted' => 0,
      'has_updates' => 1,
      'is_active' => 1,
      'is_published' => 1,
      'is_picked_for_indexing' => 0,
      'course_small_image' => 'https://craydel.fra1.cdn.digitaloceanspaces.com/00du9232hnpE9XG6rzjU.jpg',
      'course_image' => 'https://craydel.fra1.cdn.digitaloceanspaces.com/00du9232hnpE9XG6rzjU.jpg'
    ]);

    CourseAcademicDiscipline::factory()->count(2)->create();
    $this->artisan('search:course:generate-index-list');
    $this->artisan('search:course:internal_build');
    (new PushCourseDataToInternalSearchEngineCommandController(
      $courses->first()->course_code
    ))->make()->push();


    $this->seeInDatabase((new CourseSearchIndexList())->getTable(), [
      'course_code' => $courses->first()->course_code,
      'is_pushed_to_search_engine' => 1,
    ]);

    Schema::disableForeignKeyConstraints();
    DB::table((new Course())->getTable())->truncate();
    DB::table((new CourseAcademicDiscipline())->getTable())->truncate();

    Schema::enableForeignKeyConstraints();
  }
  public function testIfSystemCanPushDataIntoTheInternalSearchEngine()
  {
    $this->withoutExceptionHandling();
    Schema::disableForeignKeyConstraints();
    DB::table((new SelectedKeyphrase())->getTable())->truncate();
    Schema::enableForeignKeyConstraints();

    $courses = SelectedKeyphrase::factory()->count(2)->create();

    $this->artisan('bulk:push-key-phrases-to-search-engine');

    $this->seeInDatabase((new SelectedKeyphrase())->getTable(), [
      'course_code' => $courses->first()->course_code,
      'is_pushed_to_search_engine' => 1,
    ]);

    Schema::disableForeignKeyConstraints();
    DB::table((new SelectedKeyphrase())->getTable())->truncate();
    Schema::enableForeignKeyConstraints();
  }

  /**
   * Test if system can generate sitemap
   */
  /*public function testIfSystemCanGenerateSitemap(){
      $this->withoutExceptionHandling();
      Schema::disableForeignKeyConstraints();
      DB::table((new Course())->getTable())->truncate();
      DB::table((new CourseAcademicDiscipline())->getTable())->truncate();
      DB::table((new CourseSearchIndexList())->getTable())->truncate();
      $this->artisan('db:seed');
      Schema::enableForeignKeyConstraints();

      $courses = Course::factory()->count(1)->create([
          'is_deleted' => 0,
          'has_updates' => 1,
          'is_active' => 1,
          'is_published' => 1,
          'is_picked_for_indexing' => 0,
          'course_small_image' => 'https://craydel.fra1.cdn.digitaloceanspaces.com/00du9232hnpE9XG6rzjU.jpg',
          'course_image' => 'https://craydel.fra1.cdn.digitaloceanspaces.com/00du9232hnpE9XG6rzjU.jpg'
      ]);

      CourseAcademicDiscipline::factory()->count(2)->create();

      $this->artisan('search:course:generate-index-list');

      $response = $this->get('courses/get-sitemap',[
          'locale' => 'en'
      ]);

      dd($response->response->getContent());
  }*/

    /**
     * Test if can fetch courses based on one academic discipline
    */
    public function testIfCanFetchCoursesBasedOnOneAcademicDiscipline(){
        $this->withoutExceptionHandling();

        Schema::disableForeignKeyConstraints();
        DB::table((new Course())->getTable())->truncate();
        $this->artisan('db:seed');
        Schema::enableForeignKeyConstraints();

        $course = Course::factory()->count(1)->create([
            'is_deleted' => 0,
            'has_updates' => 1,
            'is_active' => 1,
            'is_published' => 1,
            'is_picked_for_indexing' => 0,
            'course_small_image' => 'https://craydel.fra1.cdn.digitaloceanspaces.com/00du9232hnpE9XG6rzjU.jpg',
            'course_image' => 'https://craydel.fra1.cdn.digitaloceanspaces.com/00du9232hnpE9XG6rzjU.jpg'
        ])->first();

        $this->post('courses/get-courses-per-discipline', [
            'discipline_code' => AcademicDisciplineHelper::getDisciplineCodeByID($course->discipline_code)
        ],['locale' => 'en'])->seeJsonContains([
            'status' => true,
            'message' => 'Listed'
        ])->seeJsonStructure([
            'data' => [
                '*' => [
                    'course_code',
                    'course_small_image',
                    'course_image',
                    'discipline',
                    'url_course_slug',
                    'course_name'
                ]
            ]
        ]);

        Schema::disableForeignKeyConstraints();
        DB::table((new Course())->getTable())->truncate();
        Schema::enableForeignKeyConstraints();
    }
}
