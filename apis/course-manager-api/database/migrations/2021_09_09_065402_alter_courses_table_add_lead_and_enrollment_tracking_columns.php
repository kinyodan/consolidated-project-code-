<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterCoursesTableAddLeadAndEnrollmentTrackingColumns extends Migration
{
    /**
     * Run the migrations.
     * @return void
     */
    public function up()
    {
        if(Schema::hasTable('courses')) {
            Schema::table('courses', function (Blueprint $table) {
                if (!Schema::hasColumn('courses', 'total_leads_submitted_to_crm')) {
                    $table->integer('total_leads_submitted_to_crm')->default(0)->after('course_rating');
                }

                if (!Schema::hasColumn('courses', 'total_institution_enrollments')) {
                    $table->integer('total_institution_enrollments')->default(0)->after('total_leads_submitted_to_crm');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     * @return void
     */
    public function down()
    {
        if(Schema::hasTable('courses')) {
            Schema::table('courses', function (Blueprint $table) {
                if (Schema::hasColumn('courses', 'total_leads_submitted_to_crm')) {
                    $table->dropColumn('total_leads_submitted_to_crm');
                }

                if (Schema::hasColumn('courses', 'total_institution_enrollments')) {
                    $table->dropColumn('total_institution_enrollments');
                }
            });
        }
    }
}
