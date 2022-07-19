<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AlterCoursesTableAddPathwayCoursesOptions extends Migration
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
                if(Schema::hasColumn('courses', 'graduate_level')){
                    DB::statement("ALTER table courses modify graduate_level enum('Foundation Degree', 'Examination', 'Certificate', 'Diploma', 'Degree', 'Associate Degree', 'Pathway Programme', 'Foundation Programme') null;");
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
                if(Schema::hasColumn('courses', 'graduate_level')){
                    DB::statement("ALTER table courses modify graduate_level enum('Foundation Degree', 'Examination', 'Certificate', 'Diploma', 'Degree', 'Associate Degree') null;");
                }
            });
        }
    }
}
