<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTimePickedForPushingCourseSearchIndexList extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    if (Schema::hasTable('course_search_index_list')) {
      Schema::table('course_search_index_list', function (Blueprint $table) {
        if (!Schema::hasColumn('course_search_index_list', 'time_picked_for_pushing_to_search_engine')) {
          $table->dateTime('time_picked_for_pushing_to_search_engine')
            ->after('is_pushed_to_search_engine')
            ->nullable();
        }
      });
    }
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {

    if (Schema::hasTable('course_search_index_list')) {
      Schema::table('course_search_index_list', function (Blueprint $table) {
        if (Schema::hasColumn('course_search_index_list', 'time_picked_for_pushing_to_search_engine')) {
          Schema::disableForeignKeyConstraints();
          $table->dropColumn('time_picked_for_pushing_to_search_engine');
          Schema::enableForeignKeyConstraints();
        }
      });
    }
  }
}
