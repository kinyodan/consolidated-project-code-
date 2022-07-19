<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;


class SchoolFactory extends Factory
{
  
  public function definition(): array
  {
    return [
      'curriculum_id' => 1,
      'school_code' => $this->faker->countryCode(),
      'country_code' => $this->faker->bothify(),
      'discount_value' => 10,
      'school_name' => $this->faker->name(),
      'school_email' => $this->faker->email(),
      'school_phone' => $this->faker->e164PhoneNumber(),
      'school_address' => $this->faker->name(),
      'school_physical_address' => $this->faker->name(),
      'school_website_url' => $this->faker->url(),
      'school_logo_url' => $this->faker->url(),
      'school_inverse_logo_url' => $this->faker->url(),
      'status' => 1,
    ];
  }
}
