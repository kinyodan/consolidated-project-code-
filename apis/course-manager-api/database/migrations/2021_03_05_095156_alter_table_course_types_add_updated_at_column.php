<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableCourseTypesAddUpdatedAtColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(Schema::hasTable('course_types')) {
            Schema::table('course_types', function (Blueprint $table) {
                if(!Schema::hasColumn('course_types','updated_at')){
                    $table->timestamp('updated_at')->nullable();
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
        if(Schema::hasTable('course_types')) {
            Schema::table('course_types', function (Blueprint $table) {
                if(Schema::hasColumn('course_types','updated_at')){
                    $table->dropColumn('updated_at');
                }
            });
        }
    }
}
