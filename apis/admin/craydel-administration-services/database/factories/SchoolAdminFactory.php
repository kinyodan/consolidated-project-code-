<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;


class SchoolAdminFactory extends Factory
{
   
    public function definition(): array
    {
        return [
          'school_id' => 1,
          'admin_name' => $this->faker->name(),
          'admin_email' => $this->faker->email(),
          'admin_phone' => $this->faker->phoneNumber(),
          'admin_address' => $this->faker->streetAddress(),
          'admin_role' => $this->faker->jobTitle()
        ];
    }
}
