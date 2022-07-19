<?php
namespace Database\Seeders;

use App\Http\Controllers\Helpers\CraydelHelperFunctions;
use App\Models\CourseType;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CourseTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::transaction(function () {
            DB::table((new CourseType())->getTable())->delete();
            DB::table((new CourseType())->getTable())->insert(array(
                0 => [
                    'id' => 1,
                    'name' => 'Undergraduate',
                    'slug' => CraydelHelperFunctions::slugifyString('Undergraduate'),
                    'is_deleted' => 0,
                    'is_blocked' => 0,
                    'created_at' => Carbon::now()->toDateTimeString(),
                    'deleted_at' => null
                ],
                1 => [
                    'id' => 2,
                    'name' => 'Postgraduate',
                    'slug' => CraydelHelperFunctions::slugifyString('Postgraduate'),
                    'is_deleted' => 0,
                    'is_blocked' => 0,
                    'created_at' => Carbon::now()->toDateTimeString(),
                    'deleted_at' => null
                ],
                2 => [
                    'id' => 3,
                    'name' => 'Vocational Training',
                    'slug' => CraydelHelperFunctions::slugifyString('Vocational Training'),
                    'is_deleted' => 0,
                    'is_blocked' => 0,
                    'created_at' => Carbon::now()->toDateTimeString(),
                    'deleted_at' => null
                ],
                3 => [
                    'id' => 4,
                    'name' => 'Executive Training',
                    'slug' => CraydelHelperFunctions::slugifyString('Executive Training'),
                    'is_deleted' => 0,
                    'is_blocked' => 0,
                    'created_at' => Carbon::now()->toDateTimeString(),
                    'deleted_at' => null
                ],
                4 => [
                    'id' => 5,
                    'name' => 'Examination',
                    'slug' => CraydelHelperFunctions::slugifyString('Examination'),
                    'is_deleted' => 0,
                    'is_blocked' => 0,
                    'created_at' => Carbon::now()->toDateTimeString(),
                    'deleted_at' => null
                ]/*,
                5 => [
                    'id' => 6,
                    'name' => 'Pathway Courses',
                    'slug' => CraydelHelperFunctions::slugifyString('Pathway Courses'),
                    'is_deleted' => 0,
                    'is_blocked' => 0,
                    'created_at' => Carbon::now()->toDateTimeString(),
                    'deleted_at' => null
                ]*/
            ));
        });
    }
}
