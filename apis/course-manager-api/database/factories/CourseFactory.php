<?php
namespace Database\Factories;

use App\Http\Controllers\Helpers\TestInfrastructure\CoursesModuleTestsHelper;
use App\Models\AcademicDiscipline;
use App\Models\Course;
use App\Models\CourseType;
use App\Models\LearningMode;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CourseFactory extends Factory
{

    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Course::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        $attendance_types = [
            'Full Time','Evening Classes','Part Time'
        ];

        $billing_type = [
            'Per Term','Per Semester','Per Module','Per Year','Per Unit'
        ];

        $course_duration = [
            'Years','Months','Weeks','Days'
        ];

        $graduate_level = [
            'Examination','Certificate','Diploma','Degree'
        ];

        $academic_disciplines = CoursesModuleTestsHelper::getRandomAcademicDisciplineIDs();

        return [
            'country_code' => 'KE',
            'institution_code' => 'x9qkVi0ZHA',
            'course_code' => $this->faker->randomNumber(5),
            'course_name_slug' => $this->faker->slug(5),
            'course_name' => $this->faker->company,
            'total_leads_submitted_to_crm' => $this->faker->randomElement([1, 2, 3, 4, 5, 10]),
            'course_rating' => $this->faker->randomFloat(1, 1, 5),
            'description' => $this->faker->randomHtml(200),
            'course_overview' => $this->faker->randomHtml(4, 8),
            'discipline_code' => $this->faker->randomElement($academic_disciplines),
            'course_type' => $this->faker->randomElement(call_user_func(function (){
                return collect(DB::table((new CourseType())->getTable())->select(['id'])->get())
                    ->map(function ($type){
                        return [
                            'id' => $type->id ?? null
                        ];
                    })->flatten()->toArray();
            })),
            'graduate_level' => $this->faker->randomElement($graduate_level),
            'internal_course_ranking' => $this->faker->randomNumber(2),
            'institution_course_code' => $this->faker->randomNumber(5),
            'attendance_type' => $this->faker->randomElement($attendance_types),
            'learning_mode' => LearningMode::take(1)->first()->value('id'),
            'faculty_code' => $this->faker->randomNumber(5),
            'institution_website_course_url' => $this->faker->url,
            'institution_website_application_form_url' => $this->faker->url,
            'course_requirements' => $this->faker->randomHtml(1, 8),
            'content_completeness_score' => $this->faker->randomNumber(1),
            'enrollment_details' => '[{"start_month":"June","end_month":"June","deadline":""},{"start_month":"November","end_month":"November","deadline":""},{"start_month":"April","end_month":"April","deadline":""}]',
            'enrollment_in_progress' => 0,
            'standard_fee_billing_type' => $this->faker->randomElement($billing_type),
            'standard_fee_payable' => $this->faker->randomFloat(2, 1000, 1000000),
            'standard_fee_payable_usd' => $this->faker->randomFloat(2, 1000, 1000000),
            'standard_fee_breakdown' => $this->faker->randomHtml(1, 8),
            'course_small_image' => $this->faker->imageUrl(),
            'course_image' => $this->faker->imageUrl(),
            'course_structure_breakdown' => $this->faker->randomHtml(1, 10),
            'course_duration' => $this->faker->randomNumber(1),
            'course_duration_category' => $this->faker->randomElement($course_duration),
            'maximum_scholarship_available' => $this->faker->randomNumber(1),
            'accredited_by' => $this->faker->company,
            'accredited_by_acronym' => $this->faker->companySuffix,
            'accreditation_organization_url' => $this->faker->url,
            'meta_keywords' => $this->faker->text,
            'meta_description' => $this->faker->text,
            'currency' => 'KES',
            'fees_structure_url' => $this->faker->url,
            'popularity' => $this->faker->randomFloat(6, 0, 0),
            'is_featured' => 0,
            'indexing_object_id' => Str::random(20),
            'is_active' => $this->faker->boolean(60),
            'standard_first_year_fee_payable_usd' => $this->faker->randomFloat(5),
            'foreign_student_first_year_fee_payable_usd' => $this->faker->randomFloat(5),
            'foreign_student_fee_payable_usd' => $this->faker->randomFloat(5),
        ];
    }
}
