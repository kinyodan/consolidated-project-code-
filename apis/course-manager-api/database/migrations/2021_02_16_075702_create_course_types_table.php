<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCourseTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('course_types')){
            Schema::create('course_types', function (Blueprint $table) {
                $table->integerIncrements('id');
                $table->string('name');
                $table->string('slug')->unique();
                $table->tinyInteger('is_deleted')->default(0);
                $table->tinyInteger('is_blocked')->default(0);
                $table->dateTime('created_at')->nullable();
                $table->dateTime('deleted_at')->nullable();
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
        if(Schema::hasTable('course_types')){
            Schema::disableForeignKeyConstraints();
            Schema::dropIfExists('course_types');
            Schema::enableForeignKeyConstraints();
        }
    }
}
