<?php
namespace Database\Factories;

use App\Http\Controllers\Helpers\CraydelHelperFunctions;
use App\Models\InstitutionAccreditation;
use App\Models\InstitutionAlumnus;
use App\Models\JobTitle;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class InstitutionAlumnusFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = InstitutionAlumnus::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'institution_code' => $this->faker->randomNumber(4),
            'alumni_name_slug' => CraydelHelperFunctions::slugifyString(
                $this->faker->company
            ),
            'alumni_name' => $this->faker->company,
            'graduation_year' => $this->faker->year,
            'course_taken' => $this->faker->text,
            'current_employer' => $this->faker->company,
            'current_position' => JobTitle::all()->random()->first()->id,
            'personal_profile_url' => $this->faker->url,
            'big_alumnus_image_path' => $this->faker->imageUrl(),
            'small_alumnus_image_path' => $this->faker->imageUrl(),
            'created_by' => $this->faker->email,
            'updated_by' => $this->faker->email,
            'created_at' => $this->faker->dateTime,
            'updated_at' => $this->faker->dateTime
        ];
    }
}
