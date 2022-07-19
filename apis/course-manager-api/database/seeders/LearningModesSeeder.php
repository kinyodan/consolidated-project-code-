<?php
namespace Database\Seeders;

use App\Http\Controllers\Helpers\CraydelHelperFunctions;
use App\Models\LearningMode;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LearningModesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::transaction(function () {
            DB::table((new LearningMode())->getTable())->delete();
            DB::table((new LearningMode())->getTable())->insert(array(
                0 => [
                    'id' => 1,
                    'name' => 'Online',
                    'slug' => CraydelHelperFunctions::slugifyString('Online'),
                    'is_deleted' => 0,
                    'is_blocked' => 0,
                    'created_at' => Carbon::now()->toDateTimeString(),
                    'deleted_at' => null
                ],
                1 => [
                    'id' => 2,
                    'name' => 'On Campus',
                    'slug' => CraydelHelperFunctions::slugifyString('On Campus'),
                    'is_deleted' => 0,
                    'is_blocked' => 0,
                    'created_at' => Carbon::now()->toDateTimeString(),
                    'deleted_at' => null
                ],
                2 => [
                    'id' => 3,
                    'name' => 'Blended',
                    'slug' => CraydelHelperFunctions::slugifyString('Blended'),
                    'is_deleted' => 0,
                    'is_blocked' => 0,
                    'created_at' => Carbon::now()->toDateTimeString(),
                    'deleted_at' => null
                ],
                3 => [
                    'id' => 4,
                    'name' => 'Online and Blended',
                    'slug' => CraydelHelperFunctions::slugifyString('Online and Blended'),
                    'is_deleted' => 0,
                    'is_blocked' => 0,
                    'created_at' => Carbon::now()->toDateTimeString(),
                    'deleted_at' => null
                ],
                4 => [
                    'id' => 5,
                    'name' => 'Online and On-campus',
                    'slug' => CraydelHelperFunctions::slugifyString('Online and On-campus'),
                    'is_deleted' => 0,
                    'is_blocked' => 0,
                    'created_at' => Carbon::now()->toDateTimeString(),
                    'deleted_at' => null
                ],
                5 => [
                    'id' => 6,
                    'name' => 'Online and On-campus',
                    'slug' => CraydelHelperFunctions::slugifyString('On-campus and Blended'),
                    'is_deleted' => 0,
                    'is_blocked' => 0,
                    'created_at' => Carbon::now()->toDateTimeString(),
                    'deleted_at' => null
                ]
            ));
        });
    }
}
