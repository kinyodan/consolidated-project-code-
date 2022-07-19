<?php

use App\Helpers\DBSchemaJHelper;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterCoursesTableMakeCourseColumnsNullable extends Migration
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
                if (Schema::hasColumn('courses', 'course_code')) {
                    $table->string('course_code')->nullable()->after('course_name_slug')->change();
                }

                if (Schema::hasColumn('courses', 'description')) {
                    $table->text('description')->nullable()->after('course_name_slug')->change();
                }

                if(DBSchemaJHelper::tableHasIndex('courses', 'unique_course')){
                    $table->dropIndex('unique_course');
                }
            });

            Schema::table('courses', function (Blueprint $table){
                if(!DBSchemaJHelper::tableHasIndex('courses', 'unique_course')){
                    $table->unique(['country_code', 'institution_code', 'course_name_slug'], 'unique_course');
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
    }
}
