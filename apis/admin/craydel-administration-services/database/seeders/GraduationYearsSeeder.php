<?php
namespace Database\Seeders;

use App\Models\GraduationYear;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GraduationYearsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        DB::table((new GraduationYear())->getTable())->truncate();
        DB::table((new GraduationYear())->getTable())->insert($this->generate());
    }

    /**
     * Generate years
    */
    protected function generate(): array
    {
        $start_year = 2023;
        $end_year = Carbon::now()->addYears(10)->year;
        $diff = intval($end_year - $start_year);

        $years = [[
            'id' => 1,
            'year' => '2021',
            'description' => '2021',
            'created_by' => 'System',
            'updated_by' => 'System',
            'created_at' => Carbon::create('2022', '06', '20', '14', '49', '00')->toDateTimeString(),
            'updated_at' => Carbon::create('2022', '06', '20', '14', '49', '00')->toDateTimeString(),
        ],[
            'id' => 2,
            'year' => '2022 - A',
            'description' => 'Due to COVID',
            'created_by' => 'System',
            'updated_by' => 'System',
            'created_at' => Carbon::create('2022', '06', '20', '14', '49', '00')->toDateTimeString(),
            'updated_at' => Carbon::create('2022', '06', '20', '14', '49', '00')->toDateTimeString(),
        ],[
            'id' => 3,
            'year' => '2022 - B',
            'description' => 'Due to COVID',
            'created_by' => 'System',
            'updated_by' => 'System',
            'created_at' => Carbon::create('2022', '06', '20', '14', '49', '00')->toDateTimeString(),
            'updated_at' => Carbon::create('2022', '06', '20', '14', '49', '00')->toDateTimeString(),
        ]];

        $year_id = 4;

        for ($i = 1; $i <= $diff; $i++){
            $year = $start_year++;
            $years[] = [
                'id' => $year_id++,
                'year' => $year,
                'description' => $year,
                'created_by' => 'System',
                'updated_by' => 'System',
                'created_at' => Carbon::create('2022', '06', '20', '14', '49', '00')->toDateTimeString(),
                'updated_at' => Carbon::create('2022', '06', '20', '14', '49', '00')->toDateTimeString(),
            ];
        }

        return $years;
    }
}
