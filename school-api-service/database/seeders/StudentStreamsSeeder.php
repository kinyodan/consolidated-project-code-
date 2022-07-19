<?php

namespace Database\Seeders;

use App\Models\StudentClass;
use App\Models\StudentStream;
use Illuminate\Database\Seeder;

class StudentStreamsSeeder extends Seeder
{
    /**
    * Run the database seeds.
    *
    * @return void
    */
    public function run()
    {
        //create year 7 to 12
        $data = array();
        for ($i = 1; $i <= 4; $i++ ){
            $data[] = ['stream_name' => 'Stream '.$i];
        }
        StudentStream::insert($data);
    }
}
