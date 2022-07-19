<?php
namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InstitutionTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::transaction(function () {
            DB::table('institution_types')->delete();
            DB::table('institution_types')->insert(array(
                0 => array(
                    'id' => 1,
                    'name' => 'University',
                    'slug' => 'university',
                    'description' => '',
                    'is_deleted' => 0,
                    'is_blocked' => 0,
                    'created_at' => Carbon::now()->toDateTimeString()
                ),
                1 => array(
                    'id' => 2,
                    'name' => 'College',
                    'slug' => 'college',
                    'description' => '',
                    'is_deleted' => 0,
                    'is_blocked' => 0,
                    'created_at' => Carbon::now()->toDateTimeString()
                ),
                2 => array(
                    'id' => 3,
                    'name' => 'Training Center',
                    'slug' => 'training-center',
                    'description' => '',
                    'is_deleted' => 0,
                    'is_blocked' => 0,
                    'created_at' => Carbon::now()->toDateTimeString()
                ),
                3 => array(
                    'id' => 4,
                    'name' => 'Polytechnic',
                    'slug' => 'polytechnic',
                    'description' => '',
                    'is_deleted' => 0,
                    'is_blocked' => 0,
                    'created_at' => Carbon::now()->toDateTimeString()
                ),
                4 => array(
                    'id' => 5,
                    'name' => 'Examination Center',
                    'slug' => 'examination-center',
                    'description' => '',
                    'is_deleted' => 0,
                    'is_blocked' => 0,
                    'created_at' => Carbon::now()->toDateTimeString()
                )
            ));
        });
    }
}
