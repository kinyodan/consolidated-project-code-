<?php
namespace Database\Factories;

use App\Models\ClusterSubject;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;

class ClusterSubjectFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ClusterSubject::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'cluster_id' => $this->faker->randomNumber(5),
            'subject_id' => $this->faker->randomNumber(5),
            'education_type_id' => $this->faker->randomNumber(5),
            'country_id' => $this->faker->randomNumber(5),
            'is_primary' => 0,
            'career_pathway_id' => $this->faker->randomNumber(5),
            'course_code' =>   $this->faker->randomNumber(5),
        ];
    }
}
