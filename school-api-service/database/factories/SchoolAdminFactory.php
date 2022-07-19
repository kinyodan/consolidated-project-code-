<?php

namespace Database\Factories;

use App\Models\SchoolAdmin;
use Illuminate\Database\Eloquent\Factories\Factory;

class SchoolAdminFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = SchoolAdmin::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'admin_name' => $this->faker->name,
            'admin_email' =>$this->faker->safeEmail,
            'admin_phone' =>$this->faker->phoneNumber,
            'admin_address' =>$this->faker->address,
        ];
    }
}
