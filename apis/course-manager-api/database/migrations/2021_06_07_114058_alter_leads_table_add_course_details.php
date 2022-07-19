<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterLeadsTableAddCourseDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(Schema::hasTable('leads')) {
            Schema::table('leads', function (Blueprint $table) {
                if (!Schema::hasColumn($table->getTable(), 'course_learning_mode')) {
                    $table->text('course_learning_mode')->nullable()->after('course_name');
                }

                if (!Schema::hasColumn($table->getTable(), 'current_course_intake')) {
                    $table->text('current_course_intake')->nullable()->after('course_learning_mode');
                }

                if (!Schema::hasColumn($table->getTable(), 'student_academic_level')) {
                    $table->integer('student_academic_level')->nullable()->after('current_course_intake');
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
        if(Schema::hasTable('leads')) {
            Schema::table('leads', function (Blueprint $table) {
                if (Schema::hasColumn($table->getTable(), 'course_learning_mode')) {
                    $table->dropColumn('course_learning_mode');
                }

                if (Schema::hasColumn($table->getTable(), 'current_course_intake')) {
                    $table->dropColumn('current_course_intake');
                }

                if (Schema::hasColumn($table->getTable(), 'student_academic_level')) {
                    $table->dropColumn('student_academic_level');
                }
            });
        }
    }
}
