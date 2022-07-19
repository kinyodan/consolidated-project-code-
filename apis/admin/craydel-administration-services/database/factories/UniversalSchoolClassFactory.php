<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;


class UniversalSchoolClassFactory extends Factory
{
   
    public function definition(): array
    {
        return [
            'curriculum_id' => 1,
            'class_name_slug' => $this->faker->slug(),
            'class_name' => $this->faker->name(),
        ];
    }
}
