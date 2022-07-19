<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterAddIsFlaggedColumnToCourseSearchIndexList extends Migration
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
                if (!Schema::hasColumn('course_search_index_list', 'is_flagged_deletion')) {
                    $table->tinyInteger('is_flagged_deletion')
                        ->after('is_deleted')
                        ->default(0);
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
                if (Schema::hasColumn('course_search_index_list', 'is_flagged_deletion')) {
                    Schema::disableForeignKeyConstraints();
                    DB::statement("alter table course_search_index_list modify column is_flagged_deletion decimal(8, 2) not null;");
                    Schema::enableForeignKeyConstraints();
                }
            });
        }

    }
}
