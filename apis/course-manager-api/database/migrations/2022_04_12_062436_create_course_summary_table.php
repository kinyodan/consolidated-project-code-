<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCourseSummaryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('course_enrollment_summary')) {
            Schema::create('course_enrollment_summary', function (Blueprint $table) {
                $table->integerIncrements('id');
                $table->string('course_name');
                $table->string('course_category');
                $table->string('course_type');
                $table->string('country');
                $table->string('institution');
                $table->string('enrollment_dates');
                $table->timestamps();
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
        if (Schema::hasTable('course_enrollment_summary')) {
            Schema::disableForeignKeyConstraints();
            Schema::dropIfExists('course_enrollment_summary');
            Schema::enableForeignKeyConstraints();
        }
    }
}
