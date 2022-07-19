<?php
namespace Database\Factories;

use App\Models\SelectedKeyphrase;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;

class SelectedKeyphraseFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = SelectedKeyphrase::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition() : array
    {
        return [
            'course_code' => $this->faker->randomNumber(5),
            'phrases' => $this->faker->company
        ];
    }
}
