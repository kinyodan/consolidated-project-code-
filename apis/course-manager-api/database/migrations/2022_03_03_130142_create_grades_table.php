<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGradesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('grades')) {
            Schema::create('grades', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->integer('cluster_id')->unsigned();
                $table->integer('subject_id')->unsigned();
                $table->integer('education_type_id')->unsigned();
                $table->integer('country_id')->unsigned();
                $table->tinyInteger('is_published')->default(1);
                $table->integer('grade_mean_score')->unsigned();
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
        if (Schema::hasTable('grades')) {
            Schema::disableForeignKeyConstraints();
            Schema::dropIfExists('grades');
            Schema::enableForeignKeyConstraints();
        }
    }
}
