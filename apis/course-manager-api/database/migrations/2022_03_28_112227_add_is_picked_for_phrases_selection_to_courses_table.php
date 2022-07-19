<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsPickedForPhrasesSelectionToCoursesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(Schema::hasTable('courses')) {
            Schema::table('courses', function (Blueprint $table) {
                if (!Schema::hasColumn('courses', 'is_picked_for_phrases_selection')) {
                    $table->integer('is_picked_for_phrases_selection')
                        ->after('is_picked_for_indexing')
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
        if(Schema::hasTable('courses')) {
            Schema::table('courses', function (Blueprint $table) {
                if (Schema::hasColumn('courses', 'is_picked_for_phrases_selection')) {
                    Schema::disableForeignKeyConstraints();
                    $table->dropColumn('is_picked_for_phrases_selection');
                    Schema::enableForeignKeyConstraints();
                }
            });
        }
    }
}
