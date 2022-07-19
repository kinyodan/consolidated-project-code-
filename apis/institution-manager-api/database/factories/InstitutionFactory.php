<?php
namespace Database\Factories;

use App\Http\Controllers\Helpers\CraydelHelperFunctions;
use App\Models\Country;
use App\Models\Institution;
use Illuminate\Database\Eloquent\Factories\Factory;

class InstitutionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Institution::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        $country_codes = Country::all()
            ->where('is_deleted', 0)
            ->values()
            ->map(function ($country){
                return [
                    'iso_code' => $country->iso_code
                ];
            })->flatten();

        return [
            'country_code' => $this->faker->randomElement($country_codes),
            'institution_name_slug' => CraydelHelperFunctions::slugifyString($this->faker->company),
            'institution_code' => CraydelHelperFunctions::makeRandomString(10, 'INT', false),
            'institution_type' => 1,
            'ownership_type' => $this->faker->randomElement(['Private',
                'Public',
                'Region Sponsored',
                'Community Sponsored',
                'State/Country']),
            'institution_name' => $this->faker->company,
            'is_featured' => 1,
            'description' => $this->faker->sentence,
            'profile_details' => $this->faker->randomHtml(),
            'city' => 'Nairobi',
            'email_address' => $this->faker->companyEmail,
            'academic_office_phone_number' => $this->faker->phoneNumber,
            'academic_office_email_address' => $this->faker->freeEmail,
            'academic_office_postal_address' => $this->faker->address,
            'university_postal_address' => $this->faker->address,
            'seo_keywords' => $this->faker->company,
            'seo_description' => $this->faker->company,
            'system_internal_ranking' => $this->faker->randomDigit,
            'phone_number' => $this->faker->phoneNumber,
            'country_ranking' => $this->faker->randomDigit,
            'regional_ranking' => $this->faker->randomDigit,
            'continental_ranking' => $this->faker->randomDigit,
            'global_ranking' => $this->faker->randomDigit,
            'date_registered' => $this->faker->year,
            'accredited_by_acronym' => CraydelHelperFunctions::makeAcronym($this->faker->company),
            'accredited_by' => $this->faker->company,
            'accreditation_body_url' => $this->faker->url,
            'website_url' => $this->faker->url,
            'inquiry_form_url' => $this->faker->url,
            'finance_office_phone_number' => $this->faker->phoneNumber,
            'finance_office_email_address' => $this->faker->address,
            'main_campus_physical_location' => $this->faker->address,
            'main_campus_latitude' => $this->faker->latitude,
            'main_campus_longtitude' => $this->faker->latitude,
            'is_deleted' => 0,
            'is_active' => 1,
            'created_by' => $this->faker->firstName,
            'updated_by' => $this->faker->firstName,
            'approved_by' => $this->faker->firstName,
            'created_at' => $this->faker->dateTime,
            'updated_at' => $this->faker->dateTime,
            'approved_at' => $this->faker->dateTime
        ];
    }
}
