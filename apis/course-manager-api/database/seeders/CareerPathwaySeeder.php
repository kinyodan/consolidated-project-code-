<?php

namespace Database\Seeders;

use App\Models\CareerPathway;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CareerPathwaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table((new CareerPathway())->getTable())->truncate();
        $data = array(
            0 => array(
                'id' => 1,
                'name' => 'Agriculture & Natural Resource',
                'description' => "The production, processing, marketing, distribution, financing and development of agricultural commodities and resources including food, fibre, wood products, natural resources, horticulture, and other plant and animal products & sources.",
                'image' => "https://ddasf3j8zb8ok.cloudfront.net/craydel.com/images/assessment/career_pathways_images/agriculture_and_natural_resource.png",
            ),
            1 => array(
                'id' => 2,
                'name' => 'Commerce & Accounts',
                'description' => "Financing, Services for financing and Investment planning, banking, insurance, and business financial management. ",
                'image' => "https://ddasf3j8zb8ok.cloudfront.net/craydel.com/images/assessment/career_pathways_images/commerce_and_accounts.png",
            ),
            2 => array(
                'id' => 3,
                'name' => 'Defense & Military',
                'description' => "Protective services and homeland security, including professional and technical support services. ",
                'image' => "https://ddasf3j8zb8ok.cloudfront.net/craydel.com/images/assessment/career_pathways_images/defence_and_military.png",
            ),
            3 => array(
                'id' => 4,
                'name' => 'Designing & Art',
                'description' => "Designing, producing, exhibiting, writing, and publishing multimedia content, including visual and performing arts, and design, journalism, and entertainment services.
Examples of careers in this bucket are cartoonist, animation, graphic designer, fashion designer, writer, sound engineer, desktop publisher, photographer, editor, actor, dancer, set designer, film editor, music teacher.
",
                'image' => "https://ddasf3j8zb8ok.cloudfront.net/craydel.com/images/assessment/career_pathways_images/designing_and_art.png",
            ),
            4 => array(
                'id' => 5,
                'name' => 'Education & Training',
                'description' => "Planning, managing, and providing education, training services, and related learning support activities.",
                'image' => "https://ddasf3j8zb8ok.cloudfront.net/craydel.com/images/assessment/career_pathways_images/education_and_training.png",
            ),
            5 => array(
                'id' => 6,
                'name' => 'Media & Entertainment',
                'description' => "Designing, producing, editing, performing writing and publishing, multimedia content, including visual and performing arts and design, journalism, and entertainment services. ",
                'image' => "https://ddasf3j8zb8ok.cloudfront.net/craydel.com/images/assessment/career_pathways_images/media_and_entertainment.png",
            ),
            6 => array(
                'id' => 7,
                'name' => 'Management & Marketing',
                'description' => "Planning, managing and performing marketing activities to reach organizational objectives. Business Management and administration careers encompass planning and organizing, directing and evaluating business functions essential to efficient and product business activities.",
                'image' => "https://ddasf3j8zb8ok.cloudfront.net/craydel.com/images/assessment/career_pathways_images/management_and_marketing.png",
            ),
            7 => array(
                'id' => 8,
                'name' => 'Law and Order',
                'description' => "Planning, managing, and providing legal public safety.",
                'image' => "https://ddasf3j8zb8ok.cloudfront.net/craydel.com/images/assessment/career_pathways_images/law_and_order.png",
            ),
            8 => array(
                'id' => 9,
                'name' => 'Public Administration & Government',
                'description' => "Executing governmental functions to include governance, national security, foreign service, planning revenue, taxation, regulation, and management and administration, at the local, state, and federal levels. ",
                'image' => "https://ddasf3j8zb8ok.cloudfront.net/craydel.com/images/assessment/career_pathways_images/public_administration_and_government.png",
            ),
            9 => array(
                'id' => 10,
                'name' => 'Humanistic Studies',
                'description' => "Preparing individuals for employment in career pathways that relate to families and human needs.",
                'image' => "https://ddasf3j8zb8ok.cloudfront.net/craydel.com/images/assessment/career_pathways_images/human_services.png",
            ),
            10 => array(
                'id' => 11,
                'name' => 'Health Science',
                'description' => "Planning, managing, providing, therapeutic services, diagnostic services, health information, support services and technology research and development. ",
                'image' => "https://ddasf3j8zb8ok.cloudfront.net/craydel.com/images/assessment/career_pathways_images/health_sciences.png",
            ),
            11 => array(
                'id' => 12,
                'name' => 'Engineering and Technology',
                'description' => "Engineering technology is the practical application of science and engineering to a wide range of real world problems.",
                'image' => "https://ddasf3j8zb8ok.cloudfront.net/craydel.com/images/assessment/career_pathways_images/engineering_and_technology.png",
            ),
            12 => array(
                'id' => 13,
                'name' => 'Information Technology',
                'description' => "Building linkages in IT occupations frameworks for entry levels, technical, and professional careers, related to the design, development, support and management of hardware, software, multimedia and systems integration services. ",
                'image' => "https://ddasf3j8zb8ok.cloudfront.net/craydel.com/images/assessment/career_pathways_images/information_technology.png",
            ),
            13 => array(
                'id' => 14,
                'name' => 'Science & Research',
                'description' => "Planning, managing and providing scientific research and professional and teaching services eg physical science, social science, engineering including laboratory and testing services, and research and development services. ",
                'image' => "https://ddasf3j8zb8ok.cloudfront.net/craydel.com/images/assessment/career_pathways_images/science_and_research.png",
            ),
            14 => array(
                'id' => 15,
                'name' => 'Tourism & Hospitality',
                'description' => "Encompasses the management, marketing and operations, of restaurants and other food service, lodgings, attractions, recreation events, and travel-related services. ",
                'image' => "https://ddasf3j8zb8ok.cloudfront.net/craydel.com/images/assessment/career_pathways_images/tourism_and_hospitality.png",
            )
        );

        foreach ($data as $item) {
            $id = ($item['id']) ?: '';
            $career_pathway_name = ($item['name']) ?: '';
            $career_pathway_description = ($item['description']) ?: '';
            $image = ($item['image']) ?: '';


            $itemToInsert = [
                'id' => $id,
                'career_pathway_name' => $career_pathway_name,
                'career_pathway_slug' => Str::slug($career_pathway_name, '-'),
                'career_pathway_description' => $career_pathway_description,
                'image' => $image,

            ];

            CareerPathway::firstOrCreate($itemToInsert);
        }
    }
}
