<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AlterCourseTableUpdateGraduateLevel extends Migration
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
                    DB::statement("ALTER table courses modify graduate_level enum('Foundation Degree', 'Examination', 'Certificate', 'Diploma', 'Degree', 'Associate Degree') null;");
                }

                if(Schema::hasColumn('courses', 'foreign_student_fee_billing_type')){
                    DB::statement("ALTER table courses modify foreign_student_fee_billing_type enum('Total Cost', 'Per Term', 'Per Semester', 'Per Module', 'Per Year', 'Per Unit', 'Per Session', 'Per Day') null;");
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
                    DB::statement("ALTER table courses modify graduate_level enum('Examination', 'Certificate', 'Diploma', 'Degree', 'Associate Degree') null;");
                }

                if(Schema::hasColumn('courses', 'foreign_student_fee_billing_type')){
                    DB::statement("ALTER table courses modify foreign_student_fee_billing_type enum('Total Cost', 'Per Term', 'Per Semester', 'Per Module', 'Per Year', 'Per Unit', 'Per Session', 'Per Day') null;");
                }
            });
        }
    }
}
