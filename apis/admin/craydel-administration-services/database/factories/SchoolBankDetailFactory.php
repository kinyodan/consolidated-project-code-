<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;


class SchoolBankDetailFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
      return [
        'school_id' => 1,
        'account_name' => $this->faker->name(),
        'account_number' => $this->faker->creditCardNumber(),
        'bank_name' => $this->faker->company(),
        'branch_name' => $this->faker->streetName(),
        'swift_code' => $this->faker->swiftBicNumber(),
        'status' => 1,
      ];
    }
}
