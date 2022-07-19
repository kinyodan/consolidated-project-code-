<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class QuestionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::transaction(function () {
            DB::table('questions')->delete();
            DB::table('questions')->insert( array(
                0 => array(
                    'id' => 1,
                    'question_category_id' => 1,
                    'title' => 'Course Quality',
                    'description' =>'Quality of the syllabus, course material relevance, and variety of course options.',
                    'order' => '1'

                ),
                1 => array(
                    'id' => 2,
                    'question_category_id' => 1,
                    'title' => 'Faculty Quality',
                    'description' =>'Their competence, their level of experience, their qualification, and their accessibility.',
                    'order' => '2'

                ),
                2 => array(
                    'id' => 3,
                    'question_category_id' => 1,
                    'title' => 'Study Environment',
                    'description' =>'Resources that aided teaching, the library, the lecture theatres, the labs, the equipment and online portal.',
                    'order' => '3'

                ),
                3 => array(
                    'id' => 4,
                    'question_category_id' => 2,
                    'title' => 'Industry Linkages',
                    'description' =>'Job placement assistance, partnerships with quality employers to recruit students, and career fairs.',
                    'order' => '1'

                ),
                4 => array(
                    'id' => 5,
                    'question_category_id' => 2,
                    'title' => 'Career Guidance Department',
                    'description' =>'Career readiness tools and programs, and active guidance.',
                    'order' => '2'

                ),
                5 => array(
                    'id' => 6,
                    'question_category_id' => 2,
                    'title' => "The University's Reputation",
                    'description' => "The role that the university's reputation plays in helping graduates get employment opportunities.",
                    'order' => '3'

                ),
                6 => array(
                    'id' => 7,
                    'question_category_id' => 2,
                    'title' => 'Alumni Network',
                    'description' =>'The quality, activeness, and helpfulness of the alumni network.',
                    'order' => '4'

                ),
                7 => array(
                    'id' => 8,
                    'question_category_id' => 3,
                    'title' => 'Extracurricular Activities',
                    'description' =>'Quality, inclusivity and variety of sports, clubs, societies available.',
                    'order' => '1'

                ),
                8 => array(
                    'id' => 9,
                    'question_category_id' => 3,
                    'title' => 'Safety and Support',
                    'description' =>'Safety on campus, around the area and support from the student welfare department.',
                    'order' => '2'

                ),
                9 => array(
                    'id' => 10,
                    'question_category_id' => 3,
                    'title' => 'Cultural Diversity',
                    'description' =>'Diversity in culture for the students and staff members, and the acceptance and celebration of the different cultures.',
                    'order' => '3'

                ),
                10 => array(
                    'id' => 11,
                    'question_category_id' => 3,
                    'title' => 'Financial Assistance',
                    'description' =>'Access to loans, flexible fee payment plans and scholarships.',
                    'order' => '4'

                ),
                11 => array(
                    'id' => 12,
                    'question_category_id' => 4,
                    'title' => 'Facilities and Infrastructure',
                    'description' => 'Auditoriums, cafeterias, and other recreational facilities.',
                    'order' => '1'

                ),
                12 => array(
                    'id' => 13,
                    'question_category_id' => 4,
                    'title' => 'Proximity to Amenities',
                    'description' =>'Grocery stores, hospitals, businesses, transport, restaurants, malls, quality and affordable off-campus accommodation.',
                    'order' => '2'

                ),
                13 => array(
                    'id' => 14,
                    'question_category_id' => 4,
                    'title' => 'Housing and Accommodation',
                    'description' =>'The availability, the quality, the conditions and the affordability of on-campus accommodation.',
                    'order' => '3'

                )


            ) );
        });
    }
}
