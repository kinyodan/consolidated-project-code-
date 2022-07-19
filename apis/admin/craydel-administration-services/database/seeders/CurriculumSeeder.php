<?php
namespace Database\Seeders;

use App\Http\Controllers\Helpers\CraydelHelperFunctions;
use App\Models\Curriculum;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CurriculumSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        DB::table((new Curriculum())->getTable())->truncate();

        $data = array_merge(
            $this->kenya(),
            $this->nigeria(),
            $this->global()
        );

        DB::table((new Curriculum())->getTable())->insert($data);
    }

    /**
     * Kenya's education system
    */
    protected function kenya(): array
    {
        return [
            [
                'id' => 1,
                'curriculum_slug' => CraydelHelperFunctions::slugifyString('Kenya Certificate of Secondary Education'),
                'country_code' => 'KE',
                'curriculum_name' => 'Kenya Certificate of Secondary Education',
                'curriculum_code' => 'KCSE',
                'created_by' => 'System',
                'updated_by' => 'System',
                'is_global' => 0,
                'is_active' => 1,
                'created_at' => Carbon::create('2022', '06', '20', '14', '49', '00')->toDateTimeString(),
                'updated_at' => Carbon::create('2022', '06', '20', '14', '49', '00')->toDateTimeString()
            ],[
                'id' => 2,
                'curriculum_slug' => CraydelHelperFunctions::slugifyString('Competency Based Curriculum'),
                'country_code' => 'KE',
                'curriculum_name' => 'Competency Based Curriculum',
                'curriculum_code' => 'CBC',
                'created_by' => 'System',
                'updated_by' => 'System',
                'is_global' => 0,
                'is_active' => 1,
                'created_at' => Carbon::create('2022', '06', '20', '14', '49', '00')->toDateTimeString(),
                'updated_at' => Carbon::create('2022', '06', '20', '14', '49', '00')->toDateTimeString()
            ],[
                'id' => 3,
                'curriculum_slug' => CraydelHelperFunctions::slugifyString('Kenya Certificate of Primary Education'),
                'country_code' => 'KE',
                'curriculum_name' => 'Kenya Certificate of Primary Education',
                'curriculum_code' => 'KCPE',
                'created_by' => 'System',
                'updated_by' => 'System',
                'is_global' => 0,
                'is_active' => 1,
                'created_at' => Carbon::create('2022', '06', '20', '14', '49', '00')->toDateTimeString(),
                'updated_at' => Carbon::create('2022', '06', '20', '14', '49', '00')->toDateTimeString()
            ]
        ];
    }

    /**
     * Nigeria's education system
    */
    protected function nigeria(): array
    {
        return [
            [
                'id' => 4,
                'curriculum_slug' => CraydelHelperFunctions::slugifyString('Nigeria Certificate in Education'),
                'country_code' => 'NG',
                'curriculum_name' => 'Nigeria Certificate in Education',
                'curriculum_code' => 'NCE',
                'created_by' => 'System',
                'is_global' => 0,
                'is_active' => 0,
                'updated_by' => 'System',
                'created_at' => Carbon::create('2022', '06', '20', '14', '49', '00')->toDateTimeString(),
                'updated_at' => Carbon::create('2022', '06', '20', '14', '49', '00')->toDateTimeString()
            ],[
                'id' => 5,
                'curriculum_slug' => CraydelHelperFunctions::slugifyString('Nigerian Educational Research and Development Council'),
                'country_code' => 'NG',
                'curriculum_name' => 'Nigerian Educational Research and Development Council',
                'curriculum_code' => 'NERDC',
                'is_global' => 0,
                'is_active' => 1,
                'created_by' => 'System',
                'updated_by' => 'System',
                'created_at' => Carbon::create('2022', '06', '20', '14', '49', '00')->toDateTimeString(),
                'updated_at' => Carbon::create('2022', '06', '20', '14', '49', '00')->toDateTimeString()
            ],
        ];
    }

    /**
     * Global systems
    */
    protected function global(): array
    {
        return [[
            'id' => 6,
            'curriculum_slug' => CraydelHelperFunctions::slugifyString('International Baccalaureate'),
            'country_code' => null,
            'curriculum_name' => 'International Baccalaureate',
            'curriculum_code' => 'IB',
            'is_global' => 1,
            'is_active' => 1,
            'created_by' => 'System',
            'updated_by' => 'System',
            'created_at' => Carbon::create('2022', '06', '20', '14', '49', '00')->toDateTimeString(),
            'updated_at' => Carbon::create('2022', '06', '20', '14', '49', '00')->toDateTimeString()
        ],[
            'id' => 7,
            'curriculum_slug' => CraydelHelperFunctions::slugifyString('International General Certificate of Secondary Education'),
            'country_code' => null,
            'curriculum_name' => 'International General Certificate of Secondary Education',
            'curriculum_code' => 'IGCSE',
            'is_global' => 1,
            'is_active' => 1,
            'created_by' => 'System',
            'updated_by' => 'System',
            'created_at' => Carbon::create('2022', '06', '20', '14', '49', '00')->toDateTimeString(),
            'updated_at' => Carbon::create('2022', '06', '20', '14', '49', '00')->toDateTimeString()
        ],[
            'id' => 8,
            'curriculum_slug' => CraydelHelperFunctions::slugifyString('General Certificate of Secondary Education'),
            'country_code' => null,
            'curriculum_name' => 'General Certificate of Secondary Education',
            'curriculum_code' => 'GCSE',
            'is_global' => 1,
            'is_active' => 1,
            'created_by' => 'System',
            'updated_by' => 'System',
            'created_at' => Carbon::create('2022', '06', '20', '14', '49', '00')->toDateTimeString(),
            'updated_at' => Carbon::create('2022', '06', '20', '14', '49', '00')->toDateTimeString()
        ]];
    }
}
