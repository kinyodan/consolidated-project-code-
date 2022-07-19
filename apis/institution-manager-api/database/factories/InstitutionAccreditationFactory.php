<?php
namespace Database\Factories;

use App\Http\Controllers\Helpers\CraydelHelperFunctions;
use App\Models\InstitutionAccreditation;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class InstitutionAccreditationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = InstitutionAccreditation::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'institution_code' => $this->faker->randomNumber(4),
            'organization_name_slug' => CraydelHelperFunctions::slugifyString(
                $this->faker->company
            ),
            'organization_name' => $this->faker->company,
            'organization_acronym' => CraydelHelperFunctions::makeAcronym($this->faker->company),
            'temp_image_path' => $this->faker->imageUrl(),
            'big_organization_image' => $this->faker->imageUrl(),
            'small_organization_image' => $this->faker->imageUrl(),
            'accreditation_description' => $this->faker->text,
            'created_by' => $this->faker->email,
            'updated_by' => $this->faker->email,
            'created_at' => Carbon::now()->toDateTime(),
            'updated_at' => Carbon::now()->toDateTime()
        ];
    }
}
