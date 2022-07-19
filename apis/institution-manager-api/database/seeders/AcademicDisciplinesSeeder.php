<?php
namespace Database\Seeders;

use App\Http\Controllers\Helpers\CraydelHelperFunctions;
use App\Models\AcademicDiscipline;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AcademicDisciplinesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
            DB::table((new AcademicDiscipline())->getTable())->truncate();
            $data = array(
                0 => array(
                    'name' => 'Anthropology',
                    'code' => '6390716241',
                    'discipline_small_icon' => 'https://craydel.fra1.cdn.digitaloceanspaces.com/AcademicDisciplines/Earth-Sciences.svg',
                    'discipline_large_icon' => 'https://craydel.fra1.cdn.digitaloceanspaces.com/AcademicDisciplines/Earth-Sciences.svg',
                    'seo_page_title' => 'Anthropology - Top Masters in Anthropology Courses, Fees, Universities, Scholarships',
                    'seo_page_description' => "Discover the best Anthropology bachelor's and master's degree, diploma & certificate courses. Apply today to study in Africa and Abroad.",
                    'seo_page_h1_title' => 'Anthropology Courses',
                    'seo_page_keywords' => 'Anthropology'
                ),
                1 => array(
                    'name' => 'Archaeology',
                    'code' => '5147366224',
                    'discipline_small_icon' => 'https://craydel.fra1.cdn.digitaloceanspaces.com/AcademicDisciplines/Archaeology.svg',
                    'discipline_large_icon' => 'https://craydel.fra1.cdn.digitaloceanspaces.com/AcademicDisciplines/Archaeology.svg',
                    'seo_page_title' => 'Archaeology - Top Masters in Archaeology Courses, Fees, Universities, Scholarships',
                    'seo_page_description' => "Discover the best Archaeology bachelor's and master's degree, diploma & certificate courses. Apply today to study in Africa and Abroad.",
                    'seo_page_h1_title' => 'Archaeology Courses',
                    'seo_page_keywords' => 'Archaeology'
                ),
                2 => array(
                    'name' => 'History',
                    'code' => '8855372565',
                    'discipline_small_icon' => 'https://craydel.fra1.cdn.digitaloceanspaces.com/AcademicDisciplines/History.svg',
                    'discipline_large_icon' => 'https://craydel.fra1.cdn.digitaloceanspaces.com/AcademicDisciplines/History.svg',
                    'seo_page_title' => 'History - Top Masters in History Courses, Fees, Universities, Scholarships',
                    'seo_page_description' => "Discover the best History bachelor's and master's degree, diploma & certificate courses. Apply today to study in Africa and Abroad.",
                    'seo_page_h1_title' => 'History Courses',
                    'seo_page_keywords' => 'History'
                ),
                3 => array(
                    'name' => 'Linguistics and Languages',
                    'code' => '3522983943',
                    'discipline_small_icon' => 'https://craydel.fra1.cdn.digitaloceanspaces.com/AcademicDisciplines/Linguistics-and-Languages.svg',
                    'discipline_large_icon' => 'https://craydel.fra1.cdn.digitaloceanspaces.com/AcademicDisciplines/Linguistics-and-Languages.svg',
                    'seo_page_title' => 'Linguistics and Languages - Top Masters in Linguistics and Languages Courses, Fees, Universities, Scholarships',
                    'seo_page_description' => "Discover the best Linguistics and Languages bachelor's and master's degree, diploma & certificate courses. Apply today to study in Africa and Abroad.",
                    'seo_page_h1_title' => 'Linguistics and Languages Courses',
                    'seo_page_keywords' => 'Linguistics and Languages'
                ),
                4 => array(
                    'name' => 'Philosophy',
                    'code' => '9177416976',
                    'discipline_small_icon' => 'https://craydel.fra1.cdn.digitaloceanspaces.com/AcademicDisciplines/Philosophy.svg',
                    'discipline_large_icon' => 'https://craydel.fra1.cdn.digitaloceanspaces.com/AcademicDisciplines/Philosophy.svg',
                    'seo_page_title' => 'Philosophy - Top Masters in Philosophy Courses, Fees, Universities, Scholarships',
                    'seo_page_description' => "Discover the best Philosophy bachelor's and master's degree, diploma & certificate courses. Apply today to study in Africa and Abroad.",
                    'seo_page_h1_title' => 'Philosophy Courses',
                    'seo_page_keywords' => 'Philosophy'
                ),
                5 => array(
                    'name' => 'Religion',
                    'code' => '1405650462',
                    'discipline_small_icon' => 'https://craydel.fra1.cdn.digitaloceanspaces.com/AcademicDisciplines/Religion.svg',
                    'discipline_large_icon' => 'https://craydel.fra1.cdn.digitaloceanspaces.com/AcademicDisciplines/Religion.svg',
                    'seo_page_title' => 'Religion - Top Masters in Religion Courses, Fees, Universities, Scholarships',
                    'seo_page_description' => "Discover the best Religion bachelor's and master's degree, diploma & certificate courses. Apply today to study in Africa and Abroad.",
                    'seo_page_h1_title' => 'Religion Courses',
                    'seo_page_keywords' => 'Religion'
                ),
                6 => array(
                    'name' => 'Culinary Arts',
                    'code' => '4610825513',
                    'discipline_small_icon' => 'https://craydel.fra1.cdn.digitaloceanspaces.com/AcademicDisciplines/Culinary-Arts.svg',
                    'discipline_large_icon' => 'https://craydel.fra1.cdn.digitaloceanspaces.com/AcademicDisciplines/Culinary-Arts.svg',
                    'seo_page_title' => 'Hospitality and Tourism Management - Top Courses, Fees, Universities, Scholarships',
                    'seo_page_description' => "Discover the best tourism and hospitality management bachelor's and master's degree, diploma & certificate courses. Apply today to study in Africa and Abroad.",
                    'seo_page_h1_title' => 'Culinary Arts Courses',
                    'seo_page_keywords' => 'Culinary Arts'
                ),
                7 => array(
                    'name' => 'Literature',
                    'code' => '1590403262',
                    'discipline_small_icon' => 'https://craydel.fra1.cdn.digitaloceanspaces.com/AcademicDisciplines/Literature.svg',
                    'discipline_large_icon' => 'https://craydel.fra1.cdn.digitaloceanspaces.com/AcademicDisciplines/Literature.svg',
                    'seo_page_title' => 'Literature - Top Masters in Literature Courses, Fees, Universities, Scholarships',
                    'seo_page_description' => "Discover the best Literature bachelor's and master's degree, diploma & certificate courses. Apply today to study in Africa and Abroad.",
                    'seo_page_h1_title' => 'Literature Courses',
                    'seo_page_keywords' => 'Literature'
                ),
                8 => array(
                    'name' => 'Performing Arts',
                    'code' => '6211947535',
                    'discipline_small_icon' => 'https://craydel.fra1.cdn.digitaloceanspaces.com/AcademicDisciplines/Literature.svg',
                    'discipline_large_icon' => 'https://craydel.fra1.cdn.digitaloceanspaces.com/AcademicDisciplines/Literature.svg',
                    'seo_page_title' => 'Literature - Top Masters in Performing Arts Courses, Fees, Universities, Scholarships',
                    'seo_page_description' => "Discover the best Performing Arts bachelor's and master's degree, diploma & certificate courses. Apply today to study in Africa and Abroad.",
                    'seo_page_h1_title' => 'Performing Arts Courses',
                    'seo_page_keywords' => 'Performing Arts'
                ),
                9 => array(
                    'name' => 'Visual Arts',
                    'code' => '2421664014',
                    'discipline_small_icon' => 'https://craydel.fra1.cdn.digitaloceanspaces.com/AcademicDisciplines/Visual-Arts.svg',
                    'discipline_large_icon' => 'https://craydel.fra1.cdn.digitaloceanspaces.com/AcademicDisciplines/Visual-Arts.svg',
                    'seo_page_title' => 'Visual Arts - Top Masters in Visual Arts Courses, Fees, Universities, Scholarships',
                    'seo_page_description' => "Discover the best Visual Arts bachelor's and master's degree, diploma & certificate courses. Apply today to study in Africa and Abroad.",
                    'seo_page_h1_title' => 'Visual Arts Courses',
                    'seo_page_keywords' => 'Visual Arts'
                ),
                10 => array(
                    'name' => 'Economics',
                    'code' => '2000887492',
                    'discipline_small_icon' => 'https://craydel.fra1.cdn.digitaloceanspaces.com/AcademicDisciplines/Economics.svg',
                    'discipline_large_icon' => 'https://craydel.fra1.cdn.digitaloceanspaces.com/AcademicDisciplines/Economics.svg',
                    'seo_page_title' => 'Economics - Top Courses, Fees, Universities, Scholarships',
                    'seo_page_description' => "Discover the best economics bachelor's and master's degree, diploma & certificate courses. Apply today to study in Africa and Abroad.",
                    'seo_page_h1_title' => 'Economics Courses',
                    'seo_page_keywords' => 'Economics'
                ),
                11 => array(
                    'name' => 'Geography',
                    'code' => '8879169898',
                    'discipline_small_icon' => 'https://craydel.fra1.cdn.digitaloceanspaces.com/AcademicDisciplines/Geography.svg',
                    'discipline_large_icon' => 'https://craydel.fra1.cdn.digitaloceanspaces.com/AcademicDisciplines/Geography.svg',
                    'seo_page_title' => 'Geography - Top Masters in Geography Courses, Fees, Universities, Scholarships',
                    'seo_page_description' => "Discover the best Geography bachelor's and master's degree, diploma & certificate courses. Apply today to study in Africa and Abroad.",
                    'seo_page_h1_title' => 'Geography Courses',
                    'seo_page_keywords' => 'Geography'
                ),
                12 => array(
                    'name' => 'Ethnic and Cultural Studies',
                    'code' => '3266331915',
                    'discipline_small_icon' => 'https://craydel.fra1.cdn.digitaloceanspaces.com/AcademicDisciplines/Ethnic-and-Cultural-Studies.svg',
                    'discipline_large_icon' => 'https://craydel.fra1.cdn.digitaloceanspaces.com/AcademicDisciplines/Ethnic-and-Cultural-Studies.svg',
                    'seo_page_title' => 'Ethnic and Cultural Studies - Top Masters in Ethnic and Cultural Studies Courses, Fees, Universities, Scholarships',
                    'seo_page_description' => "Discover the best Ethnic and Cultural Studies bachelor's and master's degree, diploma & certificate courses. Apply today to study in Africa and Abroad.",
                    'seo_page_h1_title' => 'Ethnic and Cultural Studies Courses',
                    'seo_page_keywords' => 'Ethnic,Cultural Studies'
                ),
                13 => array(
                    'name' => 'Gender and Sexuality Studies',
                    'code' => '4042775291',
                    'discipline_small_icon' => 'https://craydel.fra1.cdn.digitaloceanspaces.com/AcademicDisciplines/Gender-and-Sexuality-Studies.svg',
                    'discipline_large_icon' => 'https://craydel.fra1.cdn.digitaloceanspaces.com/AcademicDisciplines/Gender-and-Sexuality-Studies.svg',
                    'seo_page_title' => 'Gender and Sexuality Studies - Top Masters in Gender and Sexuality Studies Courses, Fees, Universities, Scholarships',
                    'seo_page_description' => "Discover the best Gender and Sexuality Studies bachelor's and master's degree, diploma & certificate courses. Apply today to study in Africa and Abroad.",
                    'seo_page_h1_title' => 'Gender and Sexuality Studies Courses',
                    'seo_page_keywords' => 'Gender and Sexuality Studies'
                ),
                14 => array(
                    'name' => 'Organizational Studies',
                    'code' => '8893840779',
                    'discipline_small_icon' => 'https://craydel.fra1.cdn.digitaloceanspaces.com/AcademicDisciplines/Organizational-Studies.svg',
                    'discipline_large_icon' => 'https://craydel.fra1.cdn.digitaloceanspaces.com/AcademicDisciplines/Organizational-Studies.svg',
                    'seo_page_title' => 'Organizational Studies - Top Masters in Organizational Studies Courses, Fees, Universities, Scholarships',
                    'seo_page_description' => "Discover the best Organizational Studies bachelor's and master's degree, diploma & certificate courses. Apply today to study in Africa and Abroad.",
                    'seo_page_h1_title' => 'Organizational Studies Courses',
                    'seo_page_keywords' => 'Organizational Studies'
                ),
                15 => array(
                    'name' => 'Political Science',
                    'code' => '2320404494',
                    'discipline_small_icon' => 'https://craydel.fra1.cdn.digitaloceanspaces.com/AcademicDisciplines/Political-Science.svg',
                    'discipline_large_icon' => 'https://craydel.fra1.cdn.digitaloceanspaces.com/AcademicDisciplines/Political-Science.svg',
                    'seo_page_title' => 'Political Science - Top Courses, Fees, Universities, Scholarships',
                    'seo_page_description' => "Discover the best political science bachelor's and master's degree, diploma & certificate courses. Apply today to study in Africa and Abroad.",
                    'seo_page_h1_title' => 'Political Science Courses',
                    'seo_page_keywords' => 'Political Science'
                ),
                16 => array(
                    'name' => 'Psychology',
                    'code' => '9657248833',
                    'discipline_small_icon' => 'https://craydel.fra1.cdn.digitaloceanspaces.com/AcademicDisciplines/Psychology.svg',
                    'discipline_large_icon' => 'https://craydel.fra1.cdn.digitaloceanspaces.com/AcademicDisciplines/Psychology.svg',
                    'seo_page_title' => 'Psychology - Top Courses, Fees, Universities, Scholarships',
                    'seo_page_description' => "Discover the best psychology bachelor's and master's degree, diploma & certificate courses. Apply today to study in Africa and Abroad.",
                    'seo_page_h1_title' => 'Psychology Courses',
                    'seo_page_keywords' => 'Psychology'
                ),
                17 => array(
                    'name' => 'Sociology',
                    'code' => '7108086807',
                    'discipline_small_icon' => 'https://craydel.fra1.cdn.digitaloceanspaces.com/AcademicDisciplines/Sociology.svg',
                    'discipline_large_icon' => 'https://craydel.fra1.cdn.digitaloceanspaces.com/AcademicDisciplines/Sociology.svg',
                    'seo_page_title' => 'Sociology - Top Courses, Fees, Universities, Scholarships',
                    'seo_page_description' => "Discover the best Sociology bachelor's and master's degree, diploma & certificate courses. Apply today to study in Africa and Abroad.",
                    'seo_page_h1_title' => 'Sociology Courses',
                    'seo_page_keywords' => 'Sociology'
                ),
                18 => array(
                    'name' => 'Natural Sciences',
                    'code' => '9316086111',
                    'discipline_small_icon' => 'https://craydel.fra1.cdn.digitaloceanspaces.com/AcademicDisciplines/Natural-Sciences.svg',
                    'discipline_large_icon' => 'https://craydel.fra1.cdn.digitaloceanspaces.com/AcademicDisciplines/Natural-Sciences.svg',
                    'seo_page_title' => 'Natural Sciences - Top Courses, Fees, Universities, Scholarships',
                    'seo_page_description' => "Discover the best Natural Sciences bachelor's and master's degree, diploma & certificate courses. Apply today to study in Africa and Abroad.",
                    'seo_page_h1_title' => 'Natural Sciences Courses',
                    'seo_page_keywords' => 'Natural Sciences'
                ),
                19 => array(
                    'name' => 'Biology',
                    'code' => '6894145992',
                    'discipline_small_icon' => 'https://craydel.fra1.cdn.digitaloceanspaces.com/AcademicDisciplines/Biology.svg',
                    'discipline_large_icon' => 'https://craydel.fra1.cdn.digitaloceanspaces.com/AcademicDisciplines/Biology.svg',
                    'seo_page_title' => 'Biology - Top Courses, Fees, Universities, Scholarships',
                    'seo_page_description' => "Discover the best Biology bachelor's and master's degree, diploma & certificate courses. Apply today to study in Africa and Abroad.",
                    'seo_page_h1_title' => 'Biology Courses',
                    'seo_page_keywords' => 'Biology'
                ),
                20 => array(
                    'name' => 'Chemistry',
                    'code' => '3600040151',
                    'discipline_small_icon' => 'https://craydel.fra1.cdn.digitaloceanspaces.com/AcademicDisciplines/Chemistry.svg',
                    'discipline_large_icon' => 'https://craydel.fra1.cdn.digitaloceanspaces.com/AcademicDisciplines/Chemistry.svg',
                    'seo_page_title' => 'Chemistry - Top Courses, Fees, Universities, Scholarships',
                    'seo_page_description' => "Discover the best Chemistry bachelor's and master's degree, diploma & certificate courses. Apply today to study in Africa and Abroad.",
                    'seo_page_h1_title' => 'Chemistry Courses',
                    'seo_page_keywords' => 'Chemistry'
                ),
                21 => array(
                    'name' => 'Earth Sciences',
                    'code' => '5135712624',
                    'discipline_small_icon' => 'https://craydel.fra1.cdn.digitaloceanspaces.com/AcademicDisciplines/Earth-Sciences.svg',
                    'discipline_large_icon' => 'https://craydel.fra1.cdn.digitaloceanspaces.com/AcademicDisciplines/Earth-Sciences.svg',
                    'seo_page_title' => 'Earth Sciences - Top Courses, Fees, Universities, Scholarships',
                    'seo_page_description' => "Discover the best Earth Sciences bachelor's and master's degree, diploma & certificate courses. Apply today to study in Africa and Abroad.",
                    'seo_page_h1_title' => 'Earth Sciences Courses',
                    'seo_page_keywords' => 'Earth Sciences'
                ),
                22 => array(
                    'name' => 'Physics',
                    'code' => '2634546244',
                    'discipline_small_icon' => 'https://craydel.fra1.cdn.digitaloceanspaces.com/AcademicDisciplines/Physics.svg',
                    'discipline_large_icon' => 'https://craydel.fra1.cdn.digitaloceanspaces.com/AcademicDisciplines/Physics.svg',
                    'seo_page_title' => 'Physics - Top Courses, Fees, Universities, Scholarships',
                    'seo_page_description' => "Discover the best Physics bachelor's and master's degree, diploma & certificate courses. Apply today to study in Africa and Abroad.",
                    'seo_page_h1_title' => 'Physics Courses',
                    'seo_page_keywords' => 'Physics'
                ),
                23 => array(
                    'name' => 'Space Sciences',
                    'code' => '3699067177',
                    'discipline_small_icon' => 'https://craydel.fra1.cdn.digitaloceanspaces.com/AcademicDisciplines/Space-Sciences.svg',
                    'discipline_large_icon' => 'https://craydel.fra1.cdn.digitaloceanspaces.com/AcademicDisciplines/Space-Sciences.svg',
                    'seo_page_title' => 'Space Sciences - Top Courses, Fees, Universities, Scholarships',
                    'seo_page_description' => "Discover the best Space Sciences bachelor's and master's degree, diploma & certificate courses. Apply today to study in Africa and Abroad.",
                    'seo_page_h1_title' => 'Space Sciences Courses',
                    'seo_page_keywords' => 'Space Sciences'
                ),
                24 => array(
                    'name' => 'Astronomy',
                    'code' => '9921315598',
                    'discipline_small_icon' => 'https://craydel.fra1.cdn.digitaloceanspaces.com/AcademicDisciplines/Astronomy.svg',
                    'discipline_large_icon' => 'https://craydel.fra1.cdn.digitaloceanspaces.com/AcademicDisciplines/Astronomy.svg',
                    'seo_page_title' => 'Astronomy - Top Courses, Fees, Universities, Scholarships',
                    'seo_page_description' => "Discover the best Astronomy bachelor's and master's degree, diploma & certificate courses. Apply today to study in Africa and Abroad.",
                    'seo_page_h1_title' => 'Astronomy Courses',
                    'seo_page_keywords' => 'Astronomy'
                ),
                25 => array(
                    'name' => 'Computer Science',
                    'code' => '2058598456',
                    'discipline_small_icon' => 'https://craydel.fra1.cdn.digitaloceanspaces.com/AcademicDisciplines/Computer-Programming.svg',
                    'discipline_large_icon' => 'https://craydel.fra1.cdn.digitaloceanspaces.com/AcademicDisciplines/Computer-Programming.svg',
                    'seo_page_title' => 'Computer Sciences - Top Masters in Data Science Courses, Fees, Universities, Scholarships',
                    'seo_page_description' => "Discover the best computer sciences bachelor's and master's degree, diploma & certificate courses. Apply today to study in Africa and Abroad.",
                    'seo_page_h1_title' => 'Computer Sciences Courses',
                    'seo_page_keywords' => 'Computer Sciences'
                ),
                26 => array(
                    'name' => 'Mathematics',
                    'code' => '2366596363',
                    'discipline_small_icon' => 'https://craydel.fra1.cdn.digitaloceanspaces.com/AcademicDisciplines/Mathematics.svg',
                    'discipline_large_icon' => 'https://craydel.fra1.cdn.digitaloceanspaces.com/AcademicDisciplines/Mathematics.svg',
                    'seo_page_title' => 'Mathematics - Top Courses, Fees, Universities, Scholarships',
                    'seo_page_description' => "Discover the best Mathematics bachelor's and master's degree, diploma & certificate courses. Apply today to study in Africa and Abroad.",
                    'seo_page_h1_title' => 'Mathematics Courses',
                    'seo_page_keywords' => 'Mathematics'
                ),
                27 => array(
                    'name' => 'Pure Mathematics',
                    'code' => '8996928718',
                    'discipline_small_icon' => 'https://craydel.fra1.cdn.digitaloceanspaces.com/AcademicDisciplines/Pure-Mathematics.svg',
                    'discipline_large_icon' => 'https://craydel.fra1.cdn.digitaloceanspaces.com/AcademicDisciplines/Pure-Mathematics.svg',
                    'seo_page_title' => 'Pure Mathematics - Top Courses, Fees, Universities, Scholarships',
                    'seo_page_description' => "Discover the best Pure Mathematics bachelor's and master's degree, diploma & certificate courses. Apply today to study in Africa and Abroad.",
                    'seo_page_h1_title' => 'Pure Mathematics Courses',
                    'seo_page_keywords' => 'Pure Mathematics'
                ),
                28 => array(
                    'name' => 'Applied Mathematics',
                    'code' => '6232925786',
                    'discipline_small_icon' => 'https://craydel.fra1.cdn.digitaloceanspaces.com/AcademicDisciplines/Applied-Mathematics.svg',
                    'discipline_large_icon' => 'https://craydel.fra1.cdn.digitaloceanspaces.com/AcademicDisciplines/Applied-Mathematics.svg',
                    'seo_page_title' => 'Applied Mathematics - Top Courses, Fees, Universities, Scholarships',
                    'seo_page_description' => "Discover the best Applied Mathematics bachelor's and master's degree, diploma & certificate courses. Apply today to study in Africa and Abroad.",
                    'seo_page_h1_title' => 'Applied Mathematics Courses',
                    'seo_page_keywords' => 'Applied Mathematics'
                ),
                29 => array(
                    'name' => 'Statistics',
                    'code' => '8882073471',
                    'discipline_small_icon' => 'https://craydel.fra1.cdn.digitaloceanspaces.com/AcademicDisciplines/Statistics.svg',
                    'discipline_large_icon' => 'https://craydel.fra1.cdn.digitaloceanspaces.com/AcademicDisciplines/Statistics.svg',
                    'seo_page_title' => 'Statistics - Top Courses, Fees, Universities, Scholarships',
                    'seo_page_description' => "Discover the best Statistics bachelor's and master's degree, diploma & certificate courses. Apply today to study in Africa and Abroad.",
                    'seo_page_h1_title' => 'Statistics Courses',
                    'seo_page_keywords' => 'Statistics'
                ),
                30 => array(
                    'name' => 'Systems Science',
                    'code' => '5540322417',
                    'discipline_small_icon' => 'https://craydel.fra1.cdn.digitaloceanspaces.com/AcademicDisciplines/Systems-Science.svg',
                    'discipline_large_icon' => 'https://craydel.fra1.cdn.digitaloceanspaces.com/AcademicDisciplines/Systems-Science.svg',
                    'seo_page_title' => 'Systems Science - Top Courses, Fees, Universities, Scholarships',
                    'seo_page_description' => "Discover the best Systems Science bachelor's and master's degree, diploma & certificate courses. Apply today to study in Africa and Abroad.",
                    'seo_page_h1_title' => 'Systems Science Courses',
                    'seo_page_keywords' => 'Systems Science'
                ),
                31 => array(
                    'name' => 'Agriculture',
                    'code' => '8392796222',
                    'discipline_small_icon' => 'https://craydel.fra1.cdn.digitaloceanspaces.com/AcademicDisciplines/Agriculture.svg',
                    'discipline_large_icon' => 'https://craydel.fra1.cdn.digitaloceanspaces.com/AcademicDisciplines/Agriculture.svg',
                    'seo_page_title' => 'Agriculture - Top Courses, Fees, Universities, Scholarships',
                    'seo_page_description' => "Discover the best Agriculture bachelor's and master's degree, diploma & certificate courses. Apply today to study in Africa and Abroad.",
                    'seo_page_h1_title' => 'Agriculture Courses',
                    'seo_page_keywords' => 'Agriculture'
                ),
                32 => array(
                    'name' => 'Architecture',
                    'code' => '8433937847',
                    'discipline_small_icon' => 'https://craydel.fra1.cdn.digitaloceanspaces.com/AcademicDisciplines/Architecture%20and%20Design.svg',
                    'discipline_large_icon' => 'https://craydel.fra1.cdn.digitaloceanspaces.com/AcademicDisciplines/Architecture%20and%20Design.svg',
                    'seo_page_title' => 'Architecture - Top Courses, Fees, Universities, Scholarships',
                    'seo_page_description' => "Discover the best architecture bachelor's and master's degree, diploma & certificate courses. Apply today to study in Africa and Abroad.",
                    'seo_page_h1_title' => 'Architecture Courses',
                    'seo_page_keywords' => 'Architecture, Design'
                ),
                33 => array(
                    'name' => 'Business',
                    'code' => '2326468081',
                    'discipline_small_icon' => 'https://craydel.fra1.cdn.digitaloceanspaces.com/AcademicDisciplines/Business.svg',
                    'discipline_large_icon' => 'https://craydel.fra1.cdn.digitaloceanspaces.com/AcademicDisciplines/Business.svg',
                    'seo_page_title' => 'Business - Top MBA Courses, Fees, Universities, Scholarships',
                    'seo_page_description' => "Discover the best business bachelor's and master's degree, diploma & certificate courses. Apply today to study in Africa and Abroad.",
                    'seo_page_h1_title' => 'Business Courses',
                    'seo_page_keywords' => 'Business'
                ),
                34 => array(
                    'name' => 'Divinity',
                    'code' => '3503208299',
                    'discipline_small_icon' => 'https://craydel.fra1.cdn.digitaloceanspaces.com/AcademicDisciplines/Divinity.svg',
                    'discipline_large_icon' => 'https://craydel.fra1.cdn.digitaloceanspaces.com/AcademicDisciplines/Divinity.svg',
                    'seo_page_title' => 'Divinity - Top Courses, Fees, Universities, Scholarships',
                    'seo_page_description' => "Discover the best Divinity bachelor's and master's degree, diploma & certificate courses. Apply today to study in Africa and Abroad.",
                    'seo_page_h1_title' => 'Divinity Courses',
                    'seo_page_keywords' => 'Divinity'
                ),
                35 => array(
                    'name' => 'Education',
                    'code' => '5417750233',
                    'discipline_small_icon' => 'https://craydel.fra1.cdn.digitaloceanspaces.com/AcademicDisciplines/Education.svg',
                    'discipline_large_icon' => 'https://craydel.fra1.cdn.digitaloceanspaces.com/AcademicDisciplines/Education.svg',
                    'seo_page_title' => 'Education - Top Courses, Fees, Universities, Scholarships',
                    'seo_page_description' => "Discover the best education bachelor's and master's degree, diploma & certificate courses. Apply today to study in Africa and Abroad.",
                    'seo_page_h1_title' => 'Education Courses',
                    'seo_page_keywords' => 'Education'
                ),
                36 => array(
                    'name' => 'Engineering and Technology',
                    'code' => '1150036409',
                    'discipline_small_icon' => 'https://craydel.fra1.cdn.digitaloceanspaces.com/AcademicDisciplines/Engineering-and-Technology.svg',
                    'discipline_large_icon' => 'https://craydel.fra1.cdn.digitaloceanspaces.com/AcademicDisciplines/Engineering-and-Technology.svg',
                    'seo_page_title' => 'Engineering - Top Engineering Courses, Fees, Universities, Scholarships',
                    'seo_page_description' => "Discover the best engineering bachelor's and master's degree, diploma & certificate courses. Apply today to study in Africa and Abroad.",
                    'seo_page_h1_title' => 'Engineering and Technology Courses',
                    'seo_page_keywords' => 'Engineering, Technology'
                ),
                37 => array(
                    'name' => 'Environmental Studies and Forestry',
                    'code' => '6600688148',
                    'discipline_small_icon' => 'https://craydel.fra1.cdn.digitaloceanspaces.com/AcademicDisciplines/Environmental-Studies.svg',
                    'discipline_large_icon' => 'https://craydel.fra1.cdn.digitaloceanspaces.com/AcademicDisciplines/Environmental-Studies.svg',
                    'seo_page_title' => 'Environmental Studies and Forestry - Top Courses, Fees, Universities, Scholarships',
                    'seo_page_description' => "Discover the best Environmental Studies and Forestry bachelor's and master's degree, diploma & certificate courses. Apply today to study in Africa and Abroad.",
                    'seo_page_h1_title' => 'Environmental Studies and Forestry Courses',
                    'seo_page_keywords' => 'Environmental, Forestry'
                ),
                38 => array(
                    'name' => 'Family and Consumer Science',
                    'code' => '5880990731',
                    'discipline_small_icon' => 'https://craydel.fra1.cdn.digitaloceanspaces.com/AcademicDisciplines/Family%20and%20Consumer%20Science.svg',
                    'discipline_large_icon' => 'https://craydel.fra1.cdn.digitaloceanspaces.com/AcademicDisciplines/Family%20and%20Consumer%20Science.svg',
                    'seo_page_title' => 'Family and Consumer Science - Top Courses, Fees, Universities, Scholarships',
                    'seo_page_description' => "Discover the best Family and Consumer Science bachelor's and master's degree, diploma & certificate courses. Apply today to study in Africa and Abroad.",
                    'seo_page_h1_title' => 'Family and Consumer Science Courses',
                    'seo_page_keywords' => 'Family, Consumer Science'
                ),
                39 => array(
                    'name' => 'Sports Science',
                    'code' => '8489580497',
                    'discipline_small_icon' => 'https://craydel.fra1.cdn.digitaloceanspaces.com/AcademicDisciplines/Human%20Physical-Performance-and-Recreation.svg',
                    'discipline_large_icon' => 'https://craydel.fra1.cdn.digitaloceanspaces.com/AcademicDisciplines/Human%20Physical-Performance-and-Recreation.svg',
                    'seo_page_title' => 'Sports Science - Top Courses, Fees, Universities, Scholarships',
                    'seo_page_description' => "Discover the best Sports Science bachelor's and master's degree, diploma & certificate courses. Apply today to study in Africa and Abroad.",
                    'seo_page_h1_title' => 'Sports Science Courses',
                    'seo_page_keywords' => 'Sports Science'
                ),
                40 => array(
                    'name' => 'Communication and Media',
                    'code' => '3201974982',
                    'discipline_small_icon' => 'https://craydel.fra1.cdn.digitaloceanspaces.com/AcademicDisciplines/Journalism-Media-Studies-and-Communication.svg',
                    'discipline_large_icon' => 'https://craydel.fra1.cdn.digitaloceanspaces.com/AcademicDisciplines/Journalism-Media-Studies-and-Communication.svg',
                    'seo_page_title' => 'Communication and Media - Top Courses, Fees, Universities, Scholarships',
                    'seo_page_description' => "Discover the best Communication and Media bachelor's and master's degree, diploma & certificate courses. Apply today to study in Africa and Abroad.",
                    'seo_page_h1_title' => 'Communication and Media Courses',
                    'seo_page_keywords' => 'Communication and Media'
                ),
                41 => array(
                    'name' => 'Law',
                    'code' => '8636847764',
                    'discipline_small_icon' => 'https://craydel.fra1.cdn.digitaloceanspaces.com/AcademicDisciplines/Law.svg',
                    'discipline_large_icon' => 'https://craydel.fra1.cdn.digitaloceanspaces.com/AcademicDisciplines/Law.svg',
                    'seo_page_title' => 'Law - Top Courses, Fees, Universities, Scholarships',
                    'seo_page_description' => "Discover the best law bachelor's and master's degree, diploma & certificate courses. Apply today to study in Africa and Abroad.",
                    'seo_page_h1_title' => 'Law Courses',
                    'seo_page_keywords' => 'Law'
                ),
                42 => array(
                    'name' => 'Library and Museum Studies',
                    'code' => '6044829852',
                    'discipline_small_icon' => 'https://craydel.fra1.cdn.digitaloceanspaces.com/AcademicDisciplines/Library-and-Museum-Studies.svg',
                    'discipline_large_icon' => 'https://craydel.fra1.cdn.digitaloceanspaces.com/AcademicDisciplines/Library-and-Museum-Studies.svg',
                    'seo_page_title' => 'Library and Museum Studies - Top Courses, Fees, Universities, Scholarships',
                    'seo_page_description' => "Discover the best Library and Museum Studies bachelor's and master's degree, diploma & certificate courses. Apply today to study in Africa and Abroad.",
                    'seo_page_h1_title' => 'Library and Museum Studies Courses',
                    'seo_page_keywords' => 'Library and Museum Studies'
                ),
                43 => array(
                    'name' => 'Medicine',
                    'code' => '6842152671',
                    'discipline_small_icon' => 'https://craydel.fra1.cdn.digitaloceanspaces.com/AcademicDisciplines/Medicine.svg',
                    'discipline_large_icon' => 'https://craydel.fra1.cdn.digitaloceanspaces.com/AcademicDisciplines/Medicine.svg',
                    'seo_page_title' => 'Medicine - Top Nursing Courses, Fees, Universities, Scholarships',
                    'seo_page_description' => "Discover the best medicine bachelor's and master's degree, diploma & certificate courses. Apply today to study in Africa and Abroad.",
                    'seo_page_h1_title' => 'Medicine Courses',
                    'seo_page_keywords' => 'Medicine'
                ),
                44 => array(
                    'name' => 'Military Sciences',
                    'code' => '6329625338',
                    'discipline_small_icon' => 'https://craydel.fra1.cdn.digitaloceanspaces.com/AcademicDisciplines/Military-Sciences.svg',
                    'discipline_large_icon' => 'https://craydel.fra1.cdn.digitaloceanspaces.com/AcademicDisciplines/Military-Sciences.svg',
                    'seo_page_title' => 'Military Sciences - Top Military Sciences Courses, Fees, Universities, Scholarships',
                    'seo_page_description' => "Discover the best Military Sciences bachelor's and master's degree, diploma & certificate courses. Apply today to study in Africa and Abroad.",
                    'seo_page_h1_title' => 'Military Sciences Courses',
                    'seo_page_keywords' => 'Military Sciences'
                ),
                45 => array(
                    'name' => 'Public Administration',
                    'code' => '2100406261',
                    'discipline_small_icon' => 'https://craydel.fra1.cdn.digitaloceanspaces.com/AcademicDisciplines/Public-Administration.svg',
                    'discipline_large_icon' => 'https://craydel.fra1.cdn.digitaloceanspaces.com/AcademicDisciplines/Public-Administration.svg',
                    'seo_page_title' => 'Public Administration - Top Courses, Fees, Universities, Scholarships',
                    'seo_page_description' => "Discover the best public administration bachelor's and master's degree, diploma & certificate courses. Apply today to study in Africa and Abroad.",
                    'seo_page_h1_title' => 'Public Administration Courses',
                    'seo_page_keywords' => 'Public Administration'
                ),
                46 => array(
                    'name' => 'Public Policy',
                    'code' => '2269711426',
                    'discipline_small_icon' => 'https://craydel.fra1.cdn.digitaloceanspaces.com/AcademicDisciplines/Public-Policy.svg',
                    'discipline_large_icon' => 'https://craydel.fra1.cdn.digitaloceanspaces.com/AcademicDisciplines/Public-Policy.svg',
                    'seo_page_title' => 'Public Policy - Top Public Policy Courses, Fees, Universities, Scholarships',
                    'seo_page_description' => "Discover the best Public Policy bachelor's and master's degree, diploma & certificate courses. Apply today to study in Africa and Abroad.",
                    'seo_page_h1_title' => 'Public Policy Courses',
                    'seo_page_keywords' => 'Public Policy'
                ),
                47 => array(
                    'name' => 'Social Work',
                    'code' => '4225226933',
                    'discipline_small_icon' => 'https://craydel.fra1.cdn.digitaloceanspaces.com/AcademicDisciplines/Social-Work.svg',
                    'discipline_large_icon' => 'https://craydel.fra1.cdn.digitaloceanspaces.com/AcademicDisciplines/Social-Work.svg',
                    'seo_page_title' => 'Social Work - Top Courses, Fees, Universities, Scholarships',
                    'seo_page_description' => "Discover the best social work bachelor's and master's degree, diploma & certificate courses. Apply today to study in Africa and Abroad.",
                    'seo_page_h1_title' => 'Social Work Courses',
                    'seo_page_keywords' => 'Social Work'
                ),
                48 => array(
                    'name' => 'Transportation',
                    'code' => '2648039145',
                    'discipline_small_icon' => 'https://craydel.fra1.cdn.digitaloceanspaces.com/AcademicDisciplines/Transportation.svg',
                    'discipline_large_icon' => 'https://craydel.fra1.cdn.digitaloceanspaces.com/AcademicDisciplines/Transportation.svg',
                    'seo_page_title' => 'Transportation - Top Transportation Courses, Fees, Universities, Scholarships',
                    'seo_page_description' => "Discover the best Transportation bachelor's and master's degree, diploma & certificate courses. Apply today to study in Africa and Abroad.",
                    'seo_page_h1_title' => 'Transportation Courses',
                    'seo_page_keywords' => 'Transportation'
                ),
                49 => array(
                    'name' => 'General Studies',
                    'code' => '26480396678',
                    'discipline_small_icon' => 'https://craydel.fra1.cdn.digitaloceanspaces.com/AcademicDisciplines/Transportation.svg',
                    'discipline_large_icon' => 'https://craydel.fra1.cdn.digitaloceanspaces.com/AcademicDisciplines/Transportation.svg',
                    'seo_page_title' => 'General Studies - Top Public Policy Courses, Fees, Universities, Scholarships',
                    'seo_page_description' => "Discover the best General Studies bachelor's and master's degree, diploma & certificate courses. Apply today to study in Africa and Abroad.",
                    'seo_page_h1_title' => "General Studies Courses",
                    'seo_page_keywords' => "General Studies"
                ),
                50 => array(
                    'name' => 'Nursing',
                    'code' => '50480396667',
                    'discipline_small_icon' => 'https://craydel.fra1.cdn.digitaloceanspaces.com/AcademicDisciplines/Transportation.svg',
                    'discipline_large_icon' => 'https://craydel.fra1.cdn.digitaloceanspaces.com/AcademicDisciplines/Transportation.svg',
                    'seo_page_title' => 'Nursing - Top Public Policy Courses, Fees, Universities, Scholarships',
                    'seo_page_description' => "Discover the best Nursing certificates, bachelor's and master's degree, diploma & certificate courses. Apply today to study in Africa and Abroad.",
                    'seo_page_h1_title' => "Nursing Courses",
                    'seo_page_keywords' => "Nursing"
                ),
                51 => array(
                    'name' => 'Geology',
                    'code' => '5048039666712',
                    'discipline_small_icon' => 'https://craydel.fra1.cdn.digitaloceanspaces.com/AcademicDisciplines/Transportation.svg',
                    'discipline_large_icon' => 'https://craydel.fra1.cdn.digitaloceanspaces.com/AcademicDisciplines/Transportation.svg',
                    'seo_page_title' => 'Geology - Top Public Policy Courses, Fees, Universities, Scholarships',
                    'seo_page_description' => "Discover the best Geology certificates, bachelor's and master's degree, diploma & certificate courses. Apply today to study in Africa and Abroad.",
                    'seo_page_h1_title' => "Geology Courses",
                    'seo_page_keywords' => "Geology"
                ),
                52 => array(
                    'name' => 'Aviation & Space Sciences',
                    'code' => '5048039666714',
                    'discipline_small_icon' => 'https://craydel.fra1.cdn.digitaloceanspaces.com/AcademicDisciplines/Transportation.svg',
                    'discipline_large_icon' => 'https://craydel.fra1.cdn.digitaloceanspaces.com/AcademicDisciplines/Transportation.svg',
                    'seo_page_title' => 'Aviation & Space Sciences - Top Public Policy Courses, Fees, Universities, Scholarships',
                    'seo_page_description' => "Discover the best Aviation & Space Sciences certificates, bachelor's and master's degree, diploma & certificate courses. Apply today to study in Africa and Abroad.",
                    'seo_page_h1_title' => "Aviation & Space Sciences Courses",
                    'seo_page_keywords' => "Aviation, Space Sciences"
                ),
                53 => array(
                    'name' => 'Dentistry',
                    'code' => '5048039666715',
                    'discipline_small_icon' => 'https://craydel.fra1.cdn.digitaloceanspaces.com/AcademicDisciplines/Transportation.svg',
                    'discipline_large_icon' => 'https://craydel.fra1.cdn.digitaloceanspaces.com/AcademicDisciplines/Transportation.svg',
                    'seo_page_title' => 'Dentistry - Top Public Policy Courses, Fees, Universities, Scholarships',
                    'seo_page_description' => "Discover the best Dentistry bachelor's and master's degree, diploma & certificate courses. Apply today to study in Africa and Abroad.",
                    'seo_page_h1_title' => "Dentistry Courses",
                    'seo_page_keywords' => "Dentistry"
                ),
                54 => array(
                    'name' => 'Pharmacy',
                    'code' => '5048039666716',
                    'discipline_small_icon' => 'https://craydel.fra1.cdn.digitaloceanspaces.com/AcademicDisciplines/Transportation.svg',
                    'discipline_large_icon' => 'https://craydel.fra1.cdn.digitaloceanspaces.com/AcademicDisciplines/Transportation.svg',
                    'seo_page_title' => 'Pharmacy- Top Public Policy Courses, Fees, Universities, Scholarships',
                    'seo_page_description' => "Discover the best Pharmacy bachelor's and master's degree, diploma & certificate courses. Apply today to study in Africa and Abroad.",
                    'seo_page_h1_title' => "Pharmacy Courses",
                    'seo_page_keywords' => "Pharmacy"
                ),
                55 => array(
                    'name' => 'Nutrition',
                    'code' => '5048039666717',
                    'discipline_small_icon' => 'https://craydel.fra1.cdn.digitaloceanspaces.com/AcademicDisciplines/Transportation.svg',
                    'discipline_large_icon' => 'https://craydel.fra1.cdn.digitaloceanspaces.com/AcademicDisciplines/Transportation.svg',
                    'seo_page_title' => 'Nutrition - Top Public Policy Courses, Fees, Universities, Scholarships',
                    'seo_page_description' => "Discover the best Nutrition bachelor's and master's degree, diploma & certificate courses. Apply today to study in Africa and Abroad.",
                    'seo_page_h1_title' => "Nutrition Courses",
                    'seo_page_keywords' => "Nutrition"
                ),
                56 => array(
                    'name' => 'BioChemistry',
                    'code' => '5048039666718',
                    'discipline_small_icon' => 'https://craydel.fra1.cdn.digitaloceanspaces.com/AcademicDisciplines/Transportation.svg',
                    'discipline_large_icon' => 'https://craydel.fra1.cdn.digitaloceanspaces.com/AcademicDisciplines/Transportation.svg',
                    'seo_page_title' => 'BioChemistry - Top Public Policy Courses, Fees, Universities, Scholarships',
                    'seo_page_description' => "Discover the best BioChemistry bachelor's and master's degree, diploma & certificate courses. Apply today to study in Africa and Abroad.",
                    'seo_page_h1_title' => "BioChemistry Courses",
                    'seo_page_keywords' => "BioChemistry"
                ),
                57 => array(
                    'name' => 'Physiotherapy',
                    'code' => '5048039666719',
                    'discipline_small_icon' => 'https://craydel.fra1.cdn.digitaloceanspaces.com/AcademicDisciplines/Transportation.svg',
                    'discipline_large_icon' => 'https://craydel.fra1.cdn.digitaloceanspaces.com/AcademicDisciplines/Transportation.svg',
                    'seo_page_title' => 'Physiotherapy - Top Public Policy Courses, Fees, Universities, Scholarships',
                    'seo_page_description' => "Discover the best Physiotherapy bachelor's and master's degree, diploma & certificate courses. Apply today to study in Africa and Abroad.",
                    'seo_page_h1_title' => "Physiotherapy Courses",
                    'seo_page_keywords' => "Physiotherapy"
                ),
                58 => array(
                    'name' => 'Public Health',
                    'code' => '5048039666720',
                    'discipline_small_icon' => 'https://craydel.fra1.cdn.digitaloceanspaces.com/AcademicDisciplines/Transportation.svg',
                    'discipline_large_icon' => 'https://craydel.fra1.cdn.digitaloceanspaces.com/AcademicDisciplines/Transportation.svg',
                    'seo_page_title' => 'Public Health - Top Public Policy Courses, Fees, Universities, Scholarships',
                    'seo_page_description' => "Discover the best Public Health bachelor's and master's degree, diploma & certificate courses. Apply today to study in Africa and Abroad.",
                    'seo_page_h1_title' => "Public Health Courses",
                    'seo_page_keywords' => "Public Health"
                ),
                59 => array(
                    'name' => 'Design',
                    'code' => '5048039666721',
                    'discipline_small_icon' => 'https://craydel.fra1.cdn.digitaloceanspaces.com/AcademicDisciplines/Transportation.svg',
                    'discipline_large_icon' => 'https://craydel.fra1.cdn.digitaloceanspaces.com/AcademicDisciplines/Transportation.svg',
                    'seo_page_title' => 'Design - Top Public Policy Courses, Fees, Universities, Scholarships',
                    'seo_page_description' => "Discover the best Design certificate, bachelor's and master's degree, diploma & certificate courses. Apply today to study in Africa and Abroad.",
                    'seo_page_h1_title' => "Design Courses",
                    'seo_page_keywords' => "Design"
                ),
                60 => array(
                    'name' => 'Theology',
                    'code' => '5048039666722',
                    'discipline_small_icon' => 'https://craydel.fra1.cdn.digitaloceanspaces.com/AcademicDisciplines/Transportation.svg',
                    'discipline_large_icon' => 'https://craydel.fra1.cdn.digitaloceanspaces.com/AcademicDisciplines/Transportation.svg',
                    'seo_page_title' => 'Theology - Top Public Policy Courses, Fees, Universities, Scholarships',
                    'seo_page_description' => "Discover the best Theology certificate, bachelor's and master's degree, diploma & certificate courses. Apply today to study in Africa and Abroad.",
                    'seo_page_h1_title' => "Theology Courses",
                    'seo_page_keywords' => "Theology"
                ),
                61 => array(
                    'name' => 'Hotel Management',
                    'code' => '5048039666724',
                    'discipline_small_icon' => 'https://craydel.fra1.cdn.digitaloceanspaces.com/AcademicDisciplines/Transportation.svg',
                    'discipline_large_icon' => 'https://craydel.fra1.cdn.digitaloceanspaces.com/AcademicDisciplines/Transportation.svg',
                    'seo_page_title' => 'Hotel Management - Top Public Policy Courses, Fees, Universities, Scholarships',
                    'seo_page_description' => "Discover the best Hotel Management certificate, bachelor's and master's degree, diploma & certificate courses. Apply today to study in Africa and Abroad.",
                    'seo_page_h1_title' => "Hotel Management Courses",
                    'seo_page_keywords' => "Hotel Management"
                ),
                62 => array(
                    'name' => 'Event Management',
                    'code' => '5048039666725',
                    'discipline_small_icon' => 'https://craydel.fra1.cdn.digitaloceanspaces.com/AcademicDisciplines/Transportation.svg',
                    'discipline_large_icon' => 'https://craydel.fra1.cdn.digitaloceanspaces.com/AcademicDisciplines/Transportation.svg',
                    'seo_page_title' => 'Event Management - Top Public Policy Courses, Fees, Universities, Scholarships',
                    'seo_page_description' => "Discover the best Event Management certificate, bachelor's and master's degree, diploma & certificate courses. Apply today to study in Africa and Abroad.",
                    'seo_page_h1_title' => "Event Management Courses",
                    'seo_page_keywords' => "Event Management"
                ),
                63 => array(
                    'name' => 'Travel & Tourism',
                    'code' => '5048039666726',
                    'discipline_small_icon' => 'https://craydel.fra1.cdn.digitaloceanspaces.com/AcademicDisciplines/Transportation.svg',
                    'discipline_large_icon' => 'https://craydel.fra1.cdn.digitaloceanspaces.com/AcademicDisciplines/Transportation.svg',
                    'seo_page_title' => 'Travel & Tourism - Top Public Policy Courses, Fees, Universities, Scholarships',
                    'seo_page_description' => "Discover the best Travel & Tourism certificate, bachelor's and master's degree, diploma & certificate courses. Apply today to study in Africa and Abroad.",
                    'seo_page_h1_title' => "Travel & Tourism Courses",
                    'seo_page_keywords' => "Travel & Tourism"
                ),
                64 => array(
                    'name' => 'Interior Design',
                    'code' => '5048039666727',
                    'discipline_small_icon' => 'https://craydel.fra1.cdn.digitaloceanspaces.com/AcademicDisciplines/Transportation.svg',
                    'discipline_large_icon' => 'https://craydel.fra1.cdn.digitaloceanspaces.com/AcademicDisciplines/Transportation.svg',
                    'seo_page_title' => 'Interior Design - Top Public Policy Courses, Fees, Universities, Scholarships',
                    'seo_page_description' => "Discover the best Interior Design certificate, bachelor's and master's degree, diploma & certificate courses. Apply today to study in Africa and Abroad.",
                    'seo_page_h1_title' => "Interior Design Courses",
                    'seo_page_keywords' => "Interior Design"
                ),
                65 => array(
                    'name' => 'Criminology',
                    'code' => '5048039666728',
                    'discipline_small_icon' => 'https://craydel.fra1.cdn.digitaloceanspaces.com/AcademicDisciplines/Transportation.svg',
                    'discipline_large_icon' => 'https://craydel.fra1.cdn.digitaloceanspaces.com/AcademicDisciplines/Transportation.svg',
                    'seo_page_title' => 'Criminology - Top Public Policy Courses, Fees, Universities, Scholarships',
                    'seo_page_description' => "Discover the best Criminology certificate, bachelor's and master's degree, diploma & certificate courses. Apply today to study in Africa and Abroad.",
                    'seo_page_h1_title' => "Criminology Courses",
                    'seo_page_keywords' => "Criminology"
                )
            );

            foreach ($data as $item){
                $discipline_name = ($item['name']) ?:'';
                $discipline_code = ($item['code']) ?:'';
                $discipline_small_icon = ($item['discipline_small_icon']) ?:'';
                $discipline_large_icon = ($item['discipline_large_icon']) ?:'';
                $seo_page_title = ($item['seo_page_title']) ?:'';
                $seo_page_description = ($item['seo_page_description']) ?:'';
                $seo_page_h1_title = ($item['seo_page_h1_title']) ?:'';
                $seo_page_keywords = ($item['seo_page_keywords']) ?:'';

                $itemToInsert = [
                    'discipline_name' => $discipline_name,
                    'discipline_code' => $discipline_code,
                    'discipline_small_icon' => $discipline_small_icon,
                    'discipline_large_icon' => $discipline_large_icon,
                    'seo_page_title' => $seo_page_title,
                    'seo_page_description' => $seo_page_description,
                    'seo_page_h1_title' => $seo_page_h1_title,
                    'seo_page_keywords' => $seo_page_keywords
                ];

                AcademicDiscipline::firstOrCreate($itemToInsert);
            }

    }
}
