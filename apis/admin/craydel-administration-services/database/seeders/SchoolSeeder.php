<?php


namespace Database\Seeders;

use App\Models\School;
use Faker\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SchoolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table((new School())->getTable())->truncate();
        DB::table((new School())->getTable())->insert(array(
            0 => array(
                'curriculum_id' => 1,
                'school_code' => Factory::create()->countryCode(),
                'country_code' => Factory::create()->bothify(),
                'discount_value' => 10,
                'school_name' => Factory::create()->name(),
                'school_email' => Factory::create()->email(),
                'school_phone' => Factory::create()->e164PhoneNumber(),
                'school_address' => Factory::create()->name(),
                'school_physical_address' => Factory::create()->name(),
                'school_website_url' => Factory::create()->url(),
                'school_logo_url' => Factory::create()->url(),
                'school_inverse_logo_url' => Factory::create()->url(),
                'status' => 1,
            )));
    }
}
