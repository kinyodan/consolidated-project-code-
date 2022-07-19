<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;


class SchoolStreamFactory extends Factory
{
  
  
  /**
   * @return array
   */
  public function definition(): array
    {
        return [
            'stream_name' =>$this->faker->name(),
            'stream_name_slug' => $this->faker->slug(),
            'school_id' => 1
        ];
    }
}
