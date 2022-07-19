<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEnrollmentDetailsColumnToCourseSearchIndexList extends Migration
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
                if (!Schema::hasColumn('course_search_index_list', 'is_completed_for_enrollment_details')) {
                    $table->integer('is_completed_for_enrollment_details')
                        ->after('is_picked_for_indexing')
                        ->default(0);
                }
                if (!Schema::hasColumn('course_search_index_list', 'is_picked_for_phrases_selection')) {
                    $table->integer('is_picked_for_phrases_selection')
                        ->after('is_completed_for_enrollment_details')
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
        if (Schema::hasTable('course_search_index_list')) {
            Schema::table('course_search_index_list', function (Blueprint $table) {
                if (Schema::hasColumn('course_search_index_list', 'is_completed_for_enrollment_details')) {
                    Schema::disableForeignKeyConstraints();
                    $table->dropColumn('is_completed_for_enrollment_details');
                    Schema::enableForeignKeyConstraints();
                }
                if (Schema::hasColumn('course_search_index_list', 'is_picked_for_phrases_selection')) {
                    Schema::disableForeignKeyConstraints();
                    $table->dropColumn('is_picked_for_phrases_selection');
                    Schema::enableForeignKeyConstraints();
                }
            });
        }
    }
}
