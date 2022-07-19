<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCourseTypeInstitutionAlumniTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('institution_alumni')) {
            Schema::table('institution_alumni', function (Blueprint $table) {
                if (!Schema::hasColumn('institution_alumni', 'course_category')) {
                    $table->text('course_category')->after('course_taken')->nullable();
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
        if (Schema::hasTable('institution_alumni')) {
            Schema::table('institution_alumni', function (Blueprint $table) {
                if (Schema::hasColumn('institution_alumni', 'course_category')) {
                    $table->dropColumn('course_category');
                }
            });
        }
    }
}
