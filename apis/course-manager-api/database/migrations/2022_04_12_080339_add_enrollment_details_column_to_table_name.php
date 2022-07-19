<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEnrollmentDetailsColumnToTableName extends Migration
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
                if (!Schema::hasColumn('courses', 'is_completed_for_enrollment_details')) {
                    $table->integer('is_completed_for_enrollment_details')
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
                if (Schema::hasColumn('courses', 'is_completed_for_enrollment_details')) {
                    Schema::disableForeignKeyConstraints();
                    $table->dropColumn('is_completed_for_enrollment_details');
                    Schema::enableForeignKeyConstraints();
                }
            });
        }
    }
}
