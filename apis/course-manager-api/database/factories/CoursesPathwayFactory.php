<?php
namespace Database\Factories;


use App\Models\CoursesPathway;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;

class CoursesPathwayFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CoursesPathway::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'academic_disciplines_id' => $this->faker->randomDigit,
            'career_pathways_id' =>$this->faker->randomDigit
        ];
    }
}
