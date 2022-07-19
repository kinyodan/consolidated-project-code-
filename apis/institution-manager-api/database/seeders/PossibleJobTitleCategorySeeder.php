<?php
namespace Database\Seeders;

use App\Http\Controllers\Helpers\CraydelHelperFunctions;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PossibleJobTitleCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::transaction(function () {
            DB::table('possible_job_title_categories')->delete();
            DB::table('possible_job_title_categories')->insert(array(
                0 => array(
                    'id' => 1,
                    'language' => 'en',
                    'job_title_category_slug' => CraydelHelperFunctions::slugifyString('Agriculture'),
                    'job_title_category' => 'Agriculture',
                    'description' => null,
                    'is_active' => 1,
                    'is_deleted' => 0,
                    'created_by' => 'System',
                    'updated_by' => 'System',
                    'deleted_by' => null,
                    'created_at' => Carbon::now()->toDateTime(),
                    'updated_at' => Carbon::now()->toDateTime(),
                    'deleted_at' => null
                ),
                1 => array(
                    'id' => 2,
                    'language' => 'en',
                    'job_title_category_slug' => CraydelHelperFunctions::slugifyString('Animal science'),
                    'job_title_category' => 'Animal science',
                    'description' => null,
                    'is_active' => 1,
                    'is_deleted' => 0,
                    'created_by' => 'System',
                    'updated_by' => 'System',
                    'deleted_by' => null,
                    'created_at' => Carbon::now()->toDateTime(),
                    'updated_at' => Carbon::now()->toDateTime(),
                    'deleted_at' => null
                ),
                2 => array(
                    'id' => 3,
                    'language' => 'en',
                    'job_title_category_slug' => CraydelHelperFunctions::slugifyString('Business'),
                    'job_title_category' => 'Business',
                    'description' => null,
                    'is_active' => 1,
                    'is_deleted' => 0,
                    'created_by' => 'System',
                    'updated_by' => 'System',
                    'deleted_by' => null,
                    'created_at' => Carbon::now()->toDateTime(),
                    'updated_at' => Carbon::now()->toDateTime(),
                    'deleted_at' => null
                ),
                3 => array(
                    'id' => 4,
                    'language' => 'en',
                    'job_title_category_slug' => CraydelHelperFunctions::slugifyString('Cosmetology'),
                    'job_title_category' => 'Cosmetology',
                    'description' => null,
                    'is_active' => 1,
                    'is_deleted' => 0,
                    'created_by' => 'System',
                    'updated_by' => 'System',
                    'deleted_by' => null,
                    'created_at' => Carbon::now()->toDateTime(),
                    'updated_at' => Carbon::now()->toDateTime(),
                    'deleted_at' => null
                ),
                4 => array(
                    'id' => 5,
                    'language' => 'en',
                    'job_title_category_slug' => CraydelHelperFunctions::slugifyString('Customer service'),
                    'job_title_category' => 'Customer service',
                    'description' => null,
                    'is_active' => 1,
                    'is_deleted' => 0,
                    'created_by' => 'System',
                    'updated_by' => 'System',
                    'deleted_by' => null,
                    'created_at' => Carbon::now()->toDateTime(),
                    'updated_at' => Carbon::now()->toDateTime(),
                    'deleted_at' => null
                ),
                5 => array(
                    'id' => 6,
                    'language' => 'en',
                    'job_title_category_slug' => CraydelHelperFunctions::slugifyString('Creative'),
                    'job_title_category' => 'Creative',
                    'description' => null,
                    'is_active' => 1,
                    'is_deleted' => 0,
                    'created_by' => 'System',
                    'updated_by' => 'System',
                    'deleted_by' => null,
                    'created_at' => Carbon::now()->toDateTime(),
                    'updated_at' => Carbon::now()->toDateTime(),
                    'deleted_at' => null
                ),
                6 => array(
                    'id' => 7,
                    'language' => 'en',
                    'job_title_category_slug' => CraydelHelperFunctions::slugifyString('Education'),
                    'job_title_category' => 'Education',
                    'description' => null,
                    'is_active' => 1,
                    'is_deleted' => 0,
                    'created_by' => 'System',
                    'updated_by' => 'System',
                    'deleted_by' => null,
                    'created_at' => Carbon::now()->toDateTime(),
                    'updated_at' => Carbon::now()->toDateTime(),
                    'deleted_at' => null
                ),
                7 => array(
                    'id' => 8,
                    'language' => 'en',
                    'job_title_category_slug' => CraydelHelperFunctions::slugifyString('Engineering'),
                    'job_title_category' => 'Engineering',
                    'description' => null,
                    'is_active' => 1,
                    'is_deleted' => 0,
                    'created_by' => 'System',
                    'updated_by' => 'System',
                    'deleted_by' => null,
                    'created_at' => Carbon::now()->toDateTime(),
                    'updated_at' => Carbon::now()->toDateTime(),
                    'deleted_at' => null
                ),
                8 => array(
                    'id' => 9,
                    'language' => 'en',
                    'job_title_category_slug' => CraydelHelperFunctions::slugifyString('Finance'),
                    'job_title_category' => 'Finance',
                    'description' => null,
                    'is_active' => 1,
                    'is_deleted' => 0,
                    'created_by' => 'System',
                    'updated_by' => 'System',
                    'deleted_by' => null,
                    'created_at' => Carbon::now()->toDateTime(),
                    'updated_at' => Carbon::now()->toDateTime(),
                    'deleted_at' => null
                ),
                9 => array(
                    'id' => 10,
                    'language' => 'en',
                    'job_title_category_slug' => CraydelHelperFunctions::slugifyString('Healthcare'),
                    'job_title_category' => 'Healthcare',
                    'description' => null,
                    'is_active' => 1,
                    'is_deleted' => 0,
                    'created_by' => 'System',
                    'updated_by' => 'System',
                    'deleted_by' => null,
                    'created_at' => Carbon::now()->toDateTime(),
                    'updated_at' => Carbon::now()->toDateTime(),
                    'deleted_at' => null
                ),
                10 => array(
                    'id' => 11,
                    'language' => 'en',
                    'job_title_category_slug' => CraydelHelperFunctions::slugifyString('Hospitality'),
                    'job_title_category' => 'Hospitality',
                    'description' => null,
                    'is_active' => 1,
                    'is_deleted' => 0,
                    'created_by' => 'System',
                    'updated_by' => 'System',
                    'deleted_by' => null,
                    'created_at' => Carbon::now()->toDateTime(),
                    'updated_at' => Carbon::now()->toDateTime(),
                    'deleted_at' => null
                ),
                11 => array(
                    'id' => 12,
                    'language' => 'en',
                    'job_title_category_slug' => CraydelHelperFunctions::slugifyString('Human resources'),
                    'job_title_category' => 'Human resources',
                    'description' => null,
                    'is_active' => 1,
                    'is_deleted' => 0,
                    'created_by' => 'System',
                    'updated_by' => 'System',
                    'deleted_by' => null,
                    'created_at' => Carbon::now()->toDateTime(),
                    'updated_at' => Carbon::now()->toDateTime(),
                    'deleted_at' => null
                ),
                12 => array(
                    'id' => 13,
                    'language' => 'en',
                    'job_title_category_slug' => CraydelHelperFunctions::slugifyString('Information technology'),
                    'job_title_category' => 'Information technology',
                    'description' => null,
                    'is_active' => 1,
                    'is_deleted' => 0,
                    'created_by' => 'System',
                    'updated_by' => 'System',
                    'deleted_by' => null,
                    'created_at' => Carbon::now()->toDateTime(),
                    'updated_at' => Carbon::now()->toDateTime(),
                    'deleted_at' => null
                ),
                13 => array(
                    'id' => 14,
                    'language' => 'en',
                    'job_title_category_slug' => CraydelHelperFunctions::slugifyString('Leadership'),
                    'job_title_category' => 'Leadership',
                    'description' => null,
                    'is_active' => 1,
                    'is_deleted' => 0,
                    'created_by' => 'System',
                    'updated_by' => 'System',
                    'deleted_by' => null,
                    'created_at' => Carbon::now()->toDateTime(),
                    'updated_at' => Carbon::now()->toDateTime(),
                    'deleted_at' => null
                ),
                14 => array(
                    'id' => 15,
                    'language' => 'en',
                    'job_title_category_slug' => CraydelHelperFunctions::slugifyString('Marketing'),
                    'job_title_category' => 'Marketing',
                    'description' => null,
                    'is_active' => 1,
                    'is_deleted' => 0,
                    'created_by' => 'System',
                    'updated_by' => 'System',
                    'deleted_by' => null,
                    'created_at' => Carbon::now()->toDateTime(),
                    'updated_at' => Carbon::now()->toDateTime(),
                    'deleted_at' => null
                ),
                15 => array(
                    'id' => 16,
                    'language' => 'en',
                    'job_title_category_slug' => CraydelHelperFunctions::slugifyString('Operations'),
                    'job_title_category' => 'Operations',
                    'description' => null,
                    'is_active' => 1,
                    'is_deleted' => 0,
                    'created_by' => 'System',
                    'updated_by' => 'System',
                    'deleted_by' => null,
                    'created_at' => Carbon::now()->toDateTime(),
                    'updated_at' => Carbon::now()->toDateTime(),
                    'deleted_at' => null
                ),
                16 => array(
                    'id' => 17,
                    'language' => 'en',
                    'job_title_category_slug' => CraydelHelperFunctions::slugifyString('Sales'),
                    'job_title_category' => 'Sales',
                    'description' => null,
                    'is_active' => 1,
                    'is_deleted' => 0,
                    'created_by' => 'System',
                    'updated_by' => 'System',
                    'deleted_by' => null,
                    'created_at' => Carbon::now()->toDateTime(),
                    'updated_at' => Carbon::now()->toDateTime(),
                    'deleted_at' => null
                ),
            ));
        });
    }
}
