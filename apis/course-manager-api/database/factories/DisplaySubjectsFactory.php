<?php
namespace Database\Factories;

use App\Models\DisplaySubjects;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;

class DisplaySubjectsFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = DisplaySubjects::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'country_id' =>113,
            'education_type_id'=>1,
            'academic_disciplines_id'=> 33,
            'subject_title' => $this->faker->name,
            'subject_title_description'=>$this->faker->text,
            'display_order' => 1
        ];
    }
}
