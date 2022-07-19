<?php

namespace Database\Factories;

use App\Models\School;
use App\Models\StudentClass;
use App\Models\StudentStream;
use Illuminate\Database\Eloquent\Factories\Factory;

class StudentStreamFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = StudentStream::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'stream_name' => $this->faker->streetName,
        ];
    }
}
