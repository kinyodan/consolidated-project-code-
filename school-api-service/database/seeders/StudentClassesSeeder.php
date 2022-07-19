<?php

namespace Database\Seeders;

use App\Models\StudentClass;
use Illuminate\Database\Seeder;

class StudentClassesSeeder extends Seeder
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
        for ($i = 7; $i <= 12; $i++ ){
            $data[] = ['class_name' => 'Year '.$i];
        }
        StudentClass::insert($data);
    }
}
