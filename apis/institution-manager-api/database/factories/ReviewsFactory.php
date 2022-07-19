<?php
namespace Database\Factories;

use App\Models\Institution;
use App\Models\InstitutionAlumnus;
use App\Models\Reviews;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReviewsFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Reviews::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'institution_alumni_id' => InstitutionAlumnus::all()->take(1)->first()->id,
            'institution_code' => Institution::all()->take(1)->first()->institution_code,
            'reviews' => $this->faker->paragraph,
            'up_vote' => 0,
            'down_vote' => 0,
            'flagged' => 0,
            'created_at' => Carbon::now()->toDateTimeString()
        ];
    }
}
