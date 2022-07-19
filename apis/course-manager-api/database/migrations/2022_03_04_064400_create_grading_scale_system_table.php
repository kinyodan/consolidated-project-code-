<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGradingScaleSystemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        if (!Schema::hasTable('grading_scale_system')) {
            Schema::create('grading_scale_system', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->integer('country_id')->unsigned();
                $table->integer('min')->unsigned();
                $table->integer('max')->unsigned();
                $table->string('grade_equivalent');
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

        if (Schema::hasTable('grading_scale_system')) {
            Schema::disableForeignKeyConstraints();
            Schema::dropIfExists('grading_scale_system');
            Schema::enableForeignKeyConstraints();
        }
    }
}
