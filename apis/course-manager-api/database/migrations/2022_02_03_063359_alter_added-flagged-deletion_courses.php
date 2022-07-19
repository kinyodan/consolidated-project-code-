<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AlterAddedFlaggedDeletionCourses extends Migration
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
                if (!Schema::hasColumn('courses', 'is_flagged_deletion')) {
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

        if(Schema::hasTable('courses')) {
            Schema::table('courses', function (Blueprint $table) {
                if (Schema::hasColumn('courses', 'is_flagged_deletion')) {
                    Schema::disableForeignKeyConstraints();
                    DB::statement("alter table courses modify column is_flagged_deletion decimal(8, 2) not null;");
                    Schema::enableForeignKeyConstraints();
                }
            });
        }

    }
}
