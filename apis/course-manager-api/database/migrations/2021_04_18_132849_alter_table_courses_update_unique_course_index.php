<?php
use App\Helpers\DBSchemaJHelper;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableCoursesUpdateUniqueCourseIndex extends Migration
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
                if (DBSchemaJHelper::tableHasIndex($table->getTable(), 'unique_course')) {
                    $table->dropIndex('unique_course');
                }
            });

            Schema::table('courses', function (Blueprint $table) {
                if (DBSchemaJHelper::tableHasIndex($table->getTable(), 'unique_course_name')) {
                    $table->dropIndex('unique_course_name');
                }
            });

            Schema::table('courses', function (Blueprint $table) {
                if (!DBSchemaJHelper::tableHasIndex($table->getTable(), 'unique_course_name')) {
                    $table->unique(['institution_code', 'course_name_slug', 'graduate_level'], 'unique_course_name');
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
        //
    }
}
