<?php

namespace Database\Factories;

use App\Models\School;
use Illuminate\Database\Eloquent\Factories\Factory;

class SchoolFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = School::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'school_name' => $this->faker->streetName,
            'school_email' =>$this->faker->safeEmail,
            'school_phone' =>$this->faker->phoneNumber,
            'school_address' =>$this->faker->address,
            'school_physical_address' =>$this->faker->streetAddress,
            'is_verified'=>$this->faker->boolean(50),
            'status'=>$this->faker->boolean(50),
            'discount_value'=>$this->faker->biasedNumberBetween(0,100),
            'discount_type'=>'percentage',
        ];
    }
}
