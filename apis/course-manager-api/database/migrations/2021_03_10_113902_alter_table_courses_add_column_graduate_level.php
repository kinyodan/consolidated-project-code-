<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableCoursesAddColumnGraduateLevel extends Migration
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
                if (!Schema::hasColumn('courses', 'graduate_level')) {
                    $table->enum('graduate_level', [
                        'Examination',
                        'Certificate',
                        'Diploma',
                        'Degree'
                    ])
                        ->nullable()
                        ->after('course_type');
                }

                if(!Schema::hasColumn('courses', 'internal_course_ranking')){
                    $table->float('internal_course_ranking')
                        ->default(0.1)
                        ->after('graduate_level');
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
                if (Schema::hasColumn('courses', 'graduate_level')) {
                    $table->dropColumn('graduate_level');
                }

                if(Schema::hasColumn('courses', 'internal_course_ranking')){
                    $table->dropColumn('internal_course_ranking');
                }
            });
        }
    }
}
