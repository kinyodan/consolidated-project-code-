<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCoursesCareersPathwaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('courses_careers_pathways')) {
            Schema::create('courses_careers_pathways', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('academic_disciplines_id');
                $table->unsignedBigInteger('career_pathways_id');
                $table->tinyInteger('is_published')->default(1);
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
        if(Schema::hasTable('courses_careers_pathways')){
            Schema::disableForeignKeyConstraints();
            Schema::dropIfExists('courses_careers_pathways');
            Schema::enableForeignKeyConstraints();
        }
    }
}
