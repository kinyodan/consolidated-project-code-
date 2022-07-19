<?php
namespace Database\Seeders;

use App\Http\Controllers\Courses\Commands\CustomFilterCommandController;
use App\Models\CustomFooterFilterLink;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CustomFooterFilterLinkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::transaction(function () {
            DB::table((new CustomFooterFilterLink())->getTable())->delete();
            DB::table((new CustomFooterFilterLink())->getTable())->insert(array(
                0 => [
                    'id' => 1,
                    'filter_type' => CustomFilterCommandController::CUSTOM_FILTER,
                    'title' => 'Online masters in business',
                    'attributes' => '{"course_type":"Postgraduate","discipline":"Business","learning_mode":"Online","search_term":"MBA"}',
                    'is_active' => 1,
                    'created_at' => Carbon::now()->toDateTime(),
                    'updated_at' => Carbon::now()->toDateTime(),
                    'created_by' => 'System',
                    'updated_by' => 'System'
                ]
            ));
        });
    }
}
