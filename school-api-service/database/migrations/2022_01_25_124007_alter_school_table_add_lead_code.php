<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterSchoolTableAddLeadCode extends Migration
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
                if (!Schema::hasColumn('students', 'student_lead_code')) {
                    $table->string('student_lead_code')->nullable()->after('student_opportunity_course');
                }

                if (!Schema::hasColumn('students', 'student_lead_status')) {
                    $table->string('student_lead_status')->nullable()->after('student_lead_code');
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
                if (Schema::hasColumn('students', 'student_lead_code')) {
                    $table->dropColumn('student_lead_code');
                }

                if (Schema::hasColumn('students', 'student_lead_status')) {
                    $table->dropColumn('student_lead_status');
                }
            });
        }
    }
}
