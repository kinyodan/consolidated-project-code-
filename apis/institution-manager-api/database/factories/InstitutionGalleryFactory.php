<?php
namespace Database\Factories;

use App\Http\Controllers\Helpers\CraydelHelperFunctions;
use App\Models\InstitutionGallery;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class InstitutionGalleryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = InstitutionGallery::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'institution_code' => Str::random(20),
            'asset_name_slug' => CraydelHelperFunctions::slugifyString($this->faker->company),
            'asset_name' => $this->faker->company,
            'asset_description' => $this->faker->sentence,
            'asset_position' => $this->faker->randomDigit,
            'is_featured' => 1,
            'asset_code' => Str::random(5),
            'type' => 'VideoLink',
            'video_url' => $this->faker->imageUrl(),
            'created_by' => $this->faker->dateTime,
            'created_at' => $this->faker->date()
        ];
    }
}
