<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;


class StudentFactory extends Factory
{
  
  public function definition(): array
  {
    $gender = $this->faker->randomElement(['male', 'female']);
    return [
      'student_code' => $this->faker->randomNumber(),
      'craydel_user_id' => 1,
      'school_id' => 1,
      'stream_id' => 1,
      'class_id' => 1,
      'curriculum_id' => 1,
      'student_name' => $this->faker->name(),
      'student_email' => $this->faker->email(),
      'student_phone' => $this->faker->phoneNumber(),
      'gender' => $gender,
      'nationality' => $this->faker->country(),
      'guardian_name' => $this->faker->name(),
      'guardian_email' => $this->faker->email(),
      'guardian_mobile_number' => $this->faker->phoneNumber(),
      'student_phone_country_code' => $this->faker->countryCode()
    ];
  }
}
