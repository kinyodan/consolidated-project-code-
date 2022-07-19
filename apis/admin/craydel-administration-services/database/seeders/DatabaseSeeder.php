<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run(): void
    {
        if (!app()->environment('production')) {
            Schema::disableForeignKeyConstraints();
            $this->call(CountrySeeder::class);
            $this->call(GraduationYearsSeeder::class);
            $this->call(CurriculumSeeder::class);
            $this->call(UniversalSchoolClassesSeeder::class);
            $this->call(SchoolSeeder::class);
            $this->call(StreamsSeeder::class);
            $this->call(SchoolBankDetailsTableDataSeeder::class);
            Schema::enableForeignKeyConstraints();
        }
    }
}
