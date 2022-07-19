<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class GraduationYearFactory extends Factory
{
  
  /**
   * @return array|mixed[]
   */
  public function definition(): array
  {
        return [
           'year' => $this->faker->year(),
           'description'=> $this->faker->text()
        ];
    }
}
