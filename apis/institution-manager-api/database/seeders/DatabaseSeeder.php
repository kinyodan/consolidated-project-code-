<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (!app()->environment('production')) {
            Schema::disableForeignKeyConstraints();
            $this->call(CountrySeeder::class);
            $this->call(InstitutionTypeSeeder::class);
            $this->call(PossibleJobTitleCategorySeeder::class);
            $this->call(PossibleJobTitleSeeder::class);
            $this->call(QuestionCategorySeeder::class);
            $this->call(QuestionsSeeder::class);
            $this->call(AcademicDisciplinesSeeder::class);
            $this->call(CountryIntakes::class);
            Schema::enableForeignKeyConstraints();
        }
    }
}
