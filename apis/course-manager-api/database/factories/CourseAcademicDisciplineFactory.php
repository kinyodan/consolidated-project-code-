<?php
namespace Database\Factories;

use App\Models\AcademicDiscipline;
use App\Models\Course;
use App\Models\CourseAcademicDiscipline;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;

class CourseAcademicDisciplineFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CourseAcademicDiscipline::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        $courses = call_user_func(function (){
            return collect(DB::table((new Course())->getTable())->select(['id'])->get())
                ->map(function ($type){
                    return [
                        'id' => $type->id ?? null
                    ];
                })->flatten()->toArray();
        });

        $disciplines = call_user_func(function (){
            return collect(DB::table((new AcademicDiscipline())->getTable())->select(['id'])->get())
                ->map(function ($type){
                    return [
                        'id' => $type->id ?? null
                    ];
                })->flatten()->toArray();
        });

        return [
            'courses_id' => $this->faker->randomElement($courses),
            'academic_disciplines_id' => $this->faker->randomElement($disciplines)
        ];
    }
}
