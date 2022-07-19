<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterSchoolTableAddOpportunityCode extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(Schema::hasTable('students')) {
            Schema::table('students', function (Blueprint $table) {
                if (!Schema::hasColumn('students', 'student_opportunity_code')) {
                    $table->string('student_opportunity_code')->nullable()->after('student_assessment_code');
                }

                if (!Schema::hasColumn('students', 'student_opportunity_status')) {
                    $table->string('student_opportunity_status')->nullable()->after('student_opportunity_code');
                }

                if (!Schema::hasColumn('students', 'student_opportunity_institution')) {
                    $table->text('student_opportunity_institution')->nullable()->after('student_opportunity_status');
                }

                if (!Schema::hasColumn('students', 'student_opportunity_institution_location')) {
                    $table->text('student_opportunity_institution_location')->nullable()->after('student_opportunity_institution');
                }

                if (!Schema::hasColumn('students', 'student_opportunity_intake')) {
                    $table->string('student_opportunity_intake')->nullable()->after('student_opportunity_institution_location');
                }

                if (!Schema::hasColumn('students', 'student_opportunity_course')) {
                    $table->text('student_opportunity_course')->nullable()->after('student_opportunity_intake');
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
        if(Schema::hasTable('students')) {
            Schema::table('students', function (Blueprint $table) {
                if (Schema::hasColumn('students', 'student_opportunity_code')) {
                    $table->dropColumn('student_opportunity_code');
                }

                if (Schema::hasColumn('students', 'student_opportunity_status')) {
                    $table->dropColumn('student_opportunity_status');
                }

                if (Schema::hasColumn('students', 'student_opportunity_institution')) {
                    $table->dropColumn('student_opportunity_institution');
                }

                if (Schema::hasColumn('students', 'student_opportunity_institution_location')) {
                    $table->dropColumn('student_opportunity_institution_location');
                }

                if (Schema::hasColumn('students', 'student_opportunity_intake')) {
                    $table->dropColumn('student_opportunity_intake');
                }

                if (Schema::hasColumn('students', 'student_opportunity_course')) {
                    $table->dropColumn('student_opportunity_course');
                }
            });
        }
    }
}
