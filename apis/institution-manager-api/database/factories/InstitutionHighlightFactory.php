<?php
namespace Database\Factories;

use App\Models\InstitutionHighlight;
use Illuminate\Database\Eloquent\Factories\Factory;

class InstitutionHighlightFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = InstitutionHighlight::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'institution_code' => $this->faker->randomNumber(4),
            'key_highlight' => $this->faker->company,
            'key_highlight_description' => $this->faker->text,
            'display_order' => $this->faker->randomNumber(1),
            'created_by' => $this->faker->email,
            'updated_by' => $this->faker->email,
            'created_at' => $this->faker->dateTime,
            'updated_at' => $this->faker->dateTime
        ];
    }
}
