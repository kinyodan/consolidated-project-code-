<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterCourseTableAddManualAcademicCategoryLinkageFlag extends Migration
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
                if (!Schema::hasColumn('courses', 'course_academic_category_is_generated')) {
                    $table->tinyInteger('course_academic_category_is_generated')
                        ->after('is_picked_for_unpublishing')
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
                if (Schema::hasColumn('courses', 'course_academic_category_is_generated')) {
                    $table->dropColumn('course_academic_category_is_generated');
                }
            });
        }
    }
}
