<?php
use App\Models\AcademicDiscipline;
use App\Models\Course;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCourseAcademicDiscipline extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('course_academic_discipline')) {
            Schema::create('course_academic_discipline', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('courses_id');
                $table->unsignedBigInteger('academic_disciplines_id');
                $table->unique(['courses_id', 'academic_disciplines_id'], 'unique_course_discipline');
                $table->foreign('academic_disciplines_id')
                    ->references('id')
                    ->on((new AcademicDiscipline())->getTable())
                    ->onDelete('cascade');
                $table->foreign('courses_id')
                    ->references('id')
                    ->on((new Course())->getTable())
                    ->onDelete('cascade');
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
        if(Schema::hasTable('course_academic_discipline')) {
            Schema::disableForeignKeyConstraints();
            Schema::dropIfExists('course_academic_discipline');
            Schema::enableForeignKeyConstraints();
        }
    }
}
