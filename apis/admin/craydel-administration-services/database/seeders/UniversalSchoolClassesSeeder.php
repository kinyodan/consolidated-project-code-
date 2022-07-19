<?php
namespace Database\Seeders;

use App\Http\Controllers\Helpers\CraydelHelperFunctions;
use App\Models\UniversalSchoolClass;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UniversalSchoolClassesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        DB::table((new UniversalSchoolClass())->getTable())->truncate();

        $data = array_merge(
            $this->kenya(),
            $this->nigeria(),
            $this->global()
        );

        DB::table((new UniversalSchoolClass())->getTable())->insert($data);
    }

    /**
     * Kenya system
    */
    protected function kenya(): array
    {
        return [[
            'id' => 1,
            'curriculum_id' => 1,
            'class_name_slug' => CraydelHelperFunctions::slugifyString('Form One'),
            'class_name' => 'Form One',
            'created_by' => 'System',
            'updated_by' => 'System',
            'created_at' => Carbon::create('2022', '06', '20', '14', '49', '00')->toDateTimeString(),
            'updated_at' => Carbon::create('2022', '06', '20', '14', '49', '00')->toDateTimeString(),
        ],[
            'id' => 2,
            'curriculum_id' => 1,
            'class_name_slug' => CraydelHelperFunctions::slugifyString('Form Two'),
            'class_name' => 'Form Two',
            'created_by' => 'System',
            'updated_by' => 'System',
            'created_at' => Carbon::create('2022', '06', '20', '14', '49', '00')->toDateTimeString(),
            'updated_at' => Carbon::create('2022', '06', '20', '14', '49', '00')->toDateTimeString(),
        ],[
            'id' => 3,
            'curriculum_id' => 1,
            'class_name_slug' => CraydelHelperFunctions::slugifyString('Form Three'),
            'class_name' => 'Form Three',
            'created_by' => 'System',
            'updated_by' => 'System',
            'created_at' => Carbon::create('2022', '06', '20', '14', '49', '00')->toDateTimeString(),
            'updated_at' => Carbon::create('2022', '06', '20', '14', '49', '00')->toDateTimeString(),
        ],[
            'id' => 4,
            'curriculum_id' => 1,
            'class_name_slug' => CraydelHelperFunctions::slugifyString('Form Four'),
            'class_name' => 'Form Four',
            'created_by' => 'System',
            'updated_by' => 'System',
            'created_at' => Carbon::create('2022', '06', '20', '14', '49', '00')->toDateTimeString(),
            'updated_at' => Carbon::create('2022', '06', '20', '14', '49', '00')->toDateTimeString(),
        ],[
            'id' => 5,
            'curriculum_id' => 2,
            'class_name_slug' => CraydelHelperFunctions::slugifyString('Grade Seven'),
            'class_name' => 'Grade Seven',
            'created_by' => 'System',
            'updated_by' => 'System',
            'created_at' => Carbon::create('2022', '06', '20', '14', '49', '00')->toDateTimeString(),
            'updated_at' => Carbon::create('2022', '06', '20', '14', '49', '00')->toDateTimeString(),
        ],[
            'id' => 6,
            'curriculum_id' => 2,
            'class_name_slug' => CraydelHelperFunctions::slugifyString('Grade Eight'),
            'class_name' => 'Grade Eight',
            'created_by' => 'System',
            'updated_by' => 'System',
            'created_at' => Carbon::create('2022', '06', '20', '14', '49', '00')->toDateTimeString(),
            'updated_at' => Carbon::create('2022', '06', '20', '14', '49', '00')->toDateTimeString(),
        ],[
            'id' => 7,
            'curriculum_id' => 2,
            'class_name_slug' => CraydelHelperFunctions::slugifyString('Grade Nine'),
            'class_name' => 'Grade Nine',
            'created_by' => 'System',
            'updated_by' => 'System',
            'created_at' => Carbon::create('2022', '06', '20', '14', '49', '00')->toDateTimeString(),
            'updated_at' => Carbon::create('2022', '06', '20', '14', '49', '00')->toDateTimeString(),
        ],[
            'id' => 8,
            'curriculum_id' => 2,
            'class_name_slug' => CraydelHelperFunctions::slugifyString('Grade Ten'),
            'class_name' => 'Grade Ten',
            'created_by' => 'System',
            'updated_by' => 'System',
            'created_at' => Carbon::create('2022', '06', '20', '14', '49', '00')->toDateTimeString(),
            'updated_at' => Carbon::create('2022', '06', '20', '14', '49', '00')->toDateTimeString(),
        ],[
            'id' => 9,
            'curriculum_id' => 2,
            'class_name_slug' => CraydelHelperFunctions::slugifyString('Grade Eleven'),
            'class_name' => 'Grade Eleven',
            'created_by' => 'System',
            'updated_by' => 'System',
            'created_at' => Carbon::create('2022', '06', '20', '14', '49', '00')->toDateTimeString(),
            'updated_at' => Carbon::create('2022', '06', '20', '14', '49', '00')->toDateTimeString(),
        ],[
            'id' => 10,
            'curriculum_id' => 2,
            'class_name_slug' => CraydelHelperFunctions::slugifyString('Grade Twelve'),
            'class_name' => 'Grade Twelve',
            'created_by' => 'System',
            'updated_by' => 'System',
            'created_at' => Carbon::create('2022', '06', '20', '14', '49', '00')->toDateTimeString(),
            'updated_at' => Carbon::create('2022', '06', '20', '14', '49', '00')->toDateTimeString(),
        ],[
            'id' => 11,
            'curriculum_id' => 2,
            'class_name_slug' => CraydelHelperFunctions::slugifyString('Grade Thirteen'),
            'class_name' => 'Grade Thirteen',
            'created_by' => 'System',
            'updated_by' => 'System',
            'created_at' => Carbon::create('2022', '06', '20', '14', '49', '00')->toDateTimeString(),
            'updated_at' => Carbon::create('2022', '06', '20', '14', '49', '00')->toDateTimeString(),
        ]];
    }

    /**
     * Nigeria system
    */
    protected function nigeria(): array
    {
        return [[
            'id' => 12,
            'curriculum_id' => 5,
            'class_name_slug' => CraydelHelperFunctions::slugifyString('Primary One'),
            'class_name' => 'Primary One',
            'created_by' => 'System',
            'updated_by' => 'System',
            'created_at' => Carbon::create('2022', '06', '20', '14', '49', '00')->toDateTimeString(),
            'updated_at' => Carbon::create('2022', '06', '20', '14', '49', '00')->toDateTimeString(),
        ],[
            'id' => 13,
            'curriculum_id' => 5,
            'class_name_slug' => CraydelHelperFunctions::slugifyString('Primary Two'),
            'class_name' => 'Primary Two',
            'created_by' => 'System',
            'updated_by' => 'System',
            'created_at' => Carbon::create('2022', '06', '20', '14', '49', '00')->toDateTimeString(),
            'updated_at' => Carbon::create('2022', '06', '20', '14', '49', '00')->toDateTimeString(),
        ],[
            'id' => 14,
            'curriculum_id' => 5,
            'class_name_slug' => CraydelHelperFunctions::slugifyString('Primary Three'),
            'class_name' => 'Primary Three',
            'created_by' => 'System',
            'updated_by' => 'System',
            'created_at' => Carbon::create('2022', '06', '20', '14', '49', '00')->toDateTimeString(),
            'updated_at' => Carbon::create('2022', '06', '20', '14', '49', '00')->toDateTimeString(),
        ],[
            'id' => 15,
            'curriculum_id' => 5,
            'class_name_slug' => CraydelHelperFunctions::slugifyString('Primary Four'),
            'class_name' => 'Primary Four',
            'created_by' => 'System',
            'updated_by' => 'System',
            'created_at' => Carbon::create('2022', '06', '20', '14', '49', '00')->toDateTimeString(),
            'updated_at' => Carbon::create('2022', '06', '20', '14', '49', '00')->toDateTimeString(),
        ],[
            'id' => 16,
            'curriculum_id' => 5,
            'class_name_slug' => CraydelHelperFunctions::slugifyString('Primary Five'),
            'class_name' => 'Primary Four',
            'created_by' => 'System',
            'updated_by' => 'System',
            'created_at' => Carbon::create('2022', '06', '20', '14', '49', '00')->toDateTimeString(),
            'updated_at' => Carbon::create('2022', '06', '20', '14', '49', '00')->toDateTimeString(),
        ],[
            'id' => 17,
            'curriculum_id' => 5,
            'class_name_slug' => CraydelHelperFunctions::slugifyString('Primary Six'),
            'class_name' => 'Primary Six',
            'created_by' => 'System',
            'updated_by' => 'System',
            'created_at' => Carbon::create('2022', '06', '20', '14', '49', '00')->toDateTimeString(),
            'updated_at' => Carbon::create('2022', '06', '20', '14', '49', '00')->toDateTimeString(),
        ],[
            'id' => 18,
            'curriculum_id' => 5,
            'class_name_slug' => CraydelHelperFunctions::slugifyString('Junior Secondary School one'),
            'class_name' => 'JSS One',
            'created_by' => 'System',
            'updated_by' => 'System',
            'created_at' => Carbon::create('2022', '06', '20', '14', '49', '00')->toDateTimeString(),
            'updated_at' => Carbon::create('2022', '06', '20', '14', '49', '00')->toDateTimeString(),
        ],[
            'id' => 19,
            'curriculum_id' => 5,
            'class_name_slug' => CraydelHelperFunctions::slugifyString('Junior Secondary School Two'),
            'class_name' => 'JSS Two',
            'created_by' => 'System',
            'updated_by' => 'System',
            'created_at' => Carbon::create('2022', '06', '20', '14', '49', '00')->toDateTimeString(),
            'updated_at' => Carbon::create('2022', '06', '20', '14', '49', '00')->toDateTimeString(),
        ],[
            'id' => 20,
            'curriculum_id' => 5,
            'class_name_slug' => CraydelHelperFunctions::slugifyString('Junior Secondary School Three'),
            'class_name' => 'JSS Three',
            'created_by' => 'System',
            'updated_by' => 'System',
            'created_at' => Carbon::create('2022', '06', '20', '14', '49', '00')->toDateTimeString(),
            'updated_at' => Carbon::create('2022', '06', '20', '14', '49', '00')->toDateTimeString(),
        ],[
            'id' => 21,
            'curriculum_id' => 5,
            'class_name_slug' => CraydelHelperFunctions::slugifyString('Senior Secondary School One'),
            'class_name' => 'SSS One',
            'created_by' => 'System',
            'updated_by' => 'System',
            'created_at' => Carbon::create('2022', '06', '20', '14', '49', '00')->toDateTimeString(),
            'updated_at' => Carbon::create('2022', '06', '20', '14', '49', '00')->toDateTimeString(),
        ],[
            'id' => 22,
            'curriculum_id' => 5,
            'class_name_slug' => CraydelHelperFunctions::slugifyString('Senior Secondary School Two'),
            'class_name' => 'SSS Two',
            'created_by' => 'System',
            'updated_by' => 'System',
            'created_at' => Carbon::create('2022', '06', '20', '14', '49', '00')->toDateTimeString(),
            'updated_at' => Carbon::create('2022', '06', '20', '14', '49', '00')->toDateTimeString(),
        ],[
            'id' => 23,
            'curriculum_id' => 5,
            'class_name_slug' => CraydelHelperFunctions::slugifyString('Senior Secondary School Three'),
            'class_name' => 'SSS Three',
            'created_by' => 'System',
            'updated_by' => 'System',
            'created_at' => Carbon::create('2022', '06', '20', '14', '49', '00')->toDateTimeString(),
            'updated_at' => Carbon::create('2022', '06', '20', '14', '49', '00')->toDateTimeString(),
        ]];
    }

    /**
     * Global system
    */
    protected function global(): array
    {
        return [[
            'id' => 24,
            'curriculum_id' => 6,
            'class_name_slug' => CraydelHelperFunctions::slugifyString('Grade Seven'),
            'class_name' => 'Grade Seven',
            'created_by' => 'System',
            'updated_by' => 'System',
            'created_at' => Carbon::create('2022', '06', '20', '14', '49', '00')->toDateTimeString(),
            'updated_at' => Carbon::create('2022', '06', '20', '14', '49', '00')->toDateTimeString(),
        ],[
            'id' => 25,
            'curriculum_id' => 6,
            'class_name_slug' => CraydelHelperFunctions::slugifyString('Grade Eight'),
            'class_name' => 'Grade Eight',
            'created_by' => 'System',
            'updated_by' => 'System',
            'created_at' => Carbon::create('2022', '06', '20', '14', '49', '00')->toDateTimeString(),
            'updated_at' => Carbon::create('2022', '06', '20', '14', '49', '00')->toDateTimeString(),
        ],[
            'id' => 26,
            'curriculum_id' => 6,
            'class_name_slug' => CraydelHelperFunctions::slugifyString('Grade Nine'),
            'class_name' => 'Grade Nine',
            'created_by' => 'System',
            'updated_by' => 'System',
            'created_at' => Carbon::create('2022', '06', '20', '14', '49', '00')->toDateTimeString(),
            'updated_at' => Carbon::create('2022', '06', '20', '14', '49', '00')->toDateTimeString(),
        ],[
            'id' => 27,
            'curriculum_id' => 6,
            'class_name_slug' => CraydelHelperFunctions::slugifyString('Grade Ten'),
            'class_name' => 'Grade Ten',
            'created_by' => 'System',
            'updated_by' => 'System',
            'created_at' => Carbon::create('2022', '06', '20', '14', '49', '00')->toDateTimeString(),
            'updated_at' => Carbon::create('2022', '06', '20', '14', '49', '00')->toDateTimeString(),
        ],[
            'id' => 28,
            'curriculum_id' => 6,
            'class_name_slug' => CraydelHelperFunctions::slugifyString('Grade Eleven'),
            'class_name' => 'Grade Eleven',
            'created_by' => 'System',
            'updated_by' => 'System',
            'created_at' => Carbon::create('2022', '06', '20', '14', '49', '00')->toDateTimeString(),
            'updated_at' => Carbon::create('2022', '06', '20', '14', '49', '00')->toDateTimeString(),
        ],[
            'id' => 29,
            'curriculum_id' => 6,
            'class_name_slug' => CraydelHelperFunctions::slugifyString('Grade Twelve'),
            'class_name' => 'Grade Twelve',
            'created_by' => 'System',
            'updated_by' => 'System',
            'created_at' => Carbon::create('2022', '06', '20', '14', '49', '00')->toDateTimeString(),
            'updated_at' => Carbon::create('2022', '06', '20', '14', '49', '00')->toDateTimeString(),
        ],[
            'id' => 30,
            'curriculum_id' => 6,
            'class_name_slug' => CraydelHelperFunctions::slugifyString('Grade Thirteen'),
            'class_name' => 'Grade Thirteen',
            'created_by' => 'System',
            'updated_by' => 'System',
            'created_at' => Carbon::create('2022', '06', '20', '14', '49', '00')->toDateTimeString(),
            'updated_at' => Carbon::create('2022', '06', '20', '14', '49', '00')->toDateTimeString(),
        ],[
            'id' => 31,
            'curriculum_id' => 7,
            'class_name_slug' => CraydelHelperFunctions::slugifyString('Year Nine'),
            'class_name' => 'Year Nine',
            'created_by' => 'System',
            'updated_by' => 'System',
            'created_at' => Carbon::create('2022', '06', '20', '14', '49', '00')->toDateTimeString(),
            'updated_at' => Carbon::create('2022', '06', '20', '14', '49', '00')->toDateTimeString(),
        ],[
            'id' => 32,
            'curriculum_id' => 7,
            'class_name_slug' => CraydelHelperFunctions::slugifyString('Year Ten'),
            'class_name' => 'Year Ten',
            'created_by' => 'System',
            'updated_by' => 'System',
            'created_at' => Carbon::create('2022', '06', '20', '14', '49', '00')->toDateTimeString(),
            'updated_at' => Carbon::create('2022', '06', '20', '14', '49', '00')->toDateTimeString(),
        ],[
            'id' => 33,
            'curriculum_id' => 7,
            'class_name_slug' => CraydelHelperFunctions::slugifyString('Year Eleven'),
            'class_name' => 'Year Eleven',
            'created_by' => 'System',
            'updated_by' => 'System',
            'created_at' => Carbon::create('2022', '06', '20', '14', '49', '00')->toDateTimeString(),
            'updated_at' => Carbon::create('2022', '06', '20', '14', '49', '00')->toDateTimeString(),
        ],[
            'id' => 34,
            'curriculum_id' => 7,
            'class_name_slug' => CraydelHelperFunctions::slugifyString('Year Twelve'),
            'class_name' => 'Year Twelve',
            'created_by' => 'System',
            'updated_by' => 'System',
            'created_at' => Carbon::create('2022', '06', '20', '14', '49', '00')->toDateTimeString(),
            'updated_at' => Carbon::create('2022', '06', '20', '14', '49', '00')->toDateTimeString(),
        ],[
            'id' => 35,
            'curriculum_id' => 7,
            'class_name_slug' => CraydelHelperFunctions::slugifyString('Year Thirteen'),
            'class_name' => 'Year Thirteen',
            'created_by' => 'System',
            'updated_by' => 'System',
            'created_at' => Carbon::create('2022', '06', '20', '14', '49', '00')->toDateTimeString(),
            'updated_at' => Carbon::create('2022', '06', '20', '14', '49', '00')->toDateTimeString(),
        ],[
            'id' => 36,
            'curriculum_id' => 8,
            'class_name_slug' => CraydelHelperFunctions::slugifyString('Year Nine'),
            'class_name' => 'Year Nine',
            'created_by' => 'System',
            'updated_by' => 'System',
            'created_at' => Carbon::create('2022', '06', '20', '14', '49', '00')->toDateTimeString(),
            'updated_at' => Carbon::create('2022', '06', '20', '14', '49', '00')->toDateTimeString(),
        ],[
            'id' => 37,
            'curriculum_id' => 8,
            'class_name_slug' => CraydelHelperFunctions::slugifyString('Year Ten'),
            'class_name' => 'Year Ten',
            'created_by' => 'System',
            'updated_by' => 'System',
            'created_at' => Carbon::create('2022', '06', '20', '14', '49', '00')->toDateTimeString(),
            'updated_at' => Carbon::create('2022', '06', '20', '14', '49', '00')->toDateTimeString(),
        ],[
            'id' => 38,
            'curriculum_id' => 8,
            'class_name_slug' => CraydelHelperFunctions::slugifyString('Year Eleven'),
            'class_name' => 'Year Eleven',
            'created_by' => 'System',
            'updated_by' => 'System',
            'created_at' => Carbon::create('2022', '06', '20', '14', '49', '00')->toDateTimeString(),
            'updated_at' => Carbon::create('2022', '06', '20', '14', '49', '00')->toDateTimeString(),
        ],[
            'id' => 39,
            'curriculum_id' => 8,
            'class_name_slug' => CraydelHelperFunctions::slugifyString('Year Twelve'),
            'class_name' => 'Year Twelve',
            'created_by' => 'System',
            'updated_by' => 'System',
            'created_at' => Carbon::create('2022', '06', '20', '14', '49', '00')->toDateTimeString(),
            'updated_at' => Carbon::create('2022', '06', '20', '14', '49', '00')->toDateTimeString(),
        ],[
            'id' => 40,
            'curriculum_id' => 8,
            'class_name_slug' => CraydelHelperFunctions::slugifyString('Year Thirteen'),
            'class_name' => 'Year Thirteen',
            'created_by' => 'System',
            'updated_by' => 'System',
            'created_at' => Carbon::create('2022', '06', '20', '14', '49', '00')->toDateTimeString(),
            'updated_at' => Carbon::create('2022', '06', '20', '14', '49', '00')->toDateTimeString(),
        ]];
    }
}
