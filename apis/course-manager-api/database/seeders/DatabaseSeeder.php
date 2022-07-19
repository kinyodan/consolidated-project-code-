<?php
namespace Database\Seeders;

use App\Models\AcademicDiscipline;
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
            $this->call(CourseTypeSeeder::class);
            $this->call(LearningModesSeeder::class);
            $this->call(AcademicDisciplinesSeeder::class);
            $this->call(CustomFooterFilterLinkSeeder::class);
            $this->call(CountrySeeder::class);
            $this->call(CareerPathwaySeeder::class);
            Schema::enableForeignKeyConstraints();
        }
    }
}
