<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class QuestionCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::transaction(function () {
            DB::table('question_categories')->delete();
            DB::table('question_categories')->insert( array(
                0 => array(
                    'id' => 1,
                    'title' => 'Quality of Education',

                ),
                1 => array(
                    'id' => 2,
                    'title' => 'Employment Assistance',

                ),
                2 => array(
                    'id' => 3,
                    'title' => 'Student Life',

                ),
                3 => array(
                    'id' => 4,
                    'title' => 'University Campus',

                )
            ) );
        });
    }
}
