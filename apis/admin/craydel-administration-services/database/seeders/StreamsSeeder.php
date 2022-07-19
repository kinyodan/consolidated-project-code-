<?php

namespace Database\Seeders;

use App\Http\Controllers\Helpers\CraydelHelperFunctions;
use App\Models\SchoolStream;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StreamsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        DB::table((new SchoolStream())->getTable())->truncate();
        DB::table((new SchoolStream())->getTable())->insert($this->generate());
    }

    /**
     * Generate years
     */
    protected function generate(): array
    {
        $streams=[];
        $diff = intval(40 - 1);
        $stream_id = 3;
        for ($i = 1; $i <= $diff; $i++) {
            $stream_name = "stream-" . $stream_id++;
            $stream_slug = CraydelHelperFunctions::slugifyString($stream_name);
            $streams[] = [
                'id' => $stream_id++,
                'stream_name' => "stream-" . $stream_name,
                'stream_name_slug' => $stream_slug,
                'school_id' => 1,
                'status' => 1,
                'created_by' => "system",
                'updated_by' => "system",
                'created_at' => Carbon::create('2022', '06', '20', '14', '49', '00')->toDateTimeString(),
                'updated_at' => Carbon::create('2022', '06', '20', '14', '49', '00')->toDateTimeString(),
            ];
        }
        return $streams;
    }
}
