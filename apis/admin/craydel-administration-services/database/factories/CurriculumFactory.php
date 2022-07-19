<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;


class CurriculumFactory extends Factory
{
  /**
   * Define the model's default state.
   *
   * @return array<string, mixed>
   */
  public function definition(): array
  {
    return [
      'country_code' => $this->faker->countryCode(),
      'curriculum_name' => $this->faker->name(),
      'curriculum_slug' => $this->faker->slug(),
      'curriculum_code' => $this->faker->text(5)
    ];
  }
}
