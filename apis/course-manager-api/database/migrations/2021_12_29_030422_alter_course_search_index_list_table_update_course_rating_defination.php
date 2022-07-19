<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AlterCourseSearchIndexListTableUpdateCourseRatingDefination extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(Schema::hasTable('course_search_index_list')) {
            Schema::table('course_search_index_list', function (Blueprint $table) {
                if (Schema::hasColumn('course_search_index_list', 'course_rating')) {
                    Schema::disableForeignKeyConstraints();
                    DB::statement("alter table course_search_index_list modify column course_rating decimal(8, 2) null;");
                    Schema::enableForeignKeyConstraints();
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
        if(Schema::hasTable('course_search_index_list')) {
            Schema::table('course_search_index_list', function (Blueprint $table) {
                if (Schema::hasColumn('course_search_index_list', 'course_rating')) {
                    Schema::disableForeignKeyConstraints();
                    DB::statement("alter table course_search_index_list modify column course_rating decimal(8, 2) not null;");
                    Schema::enableForeignKeyConstraints();
                }
            });
        }
    }
}
