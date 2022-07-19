<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('question_categories')) {
            Schema::create('questions', function (Blueprint $table) {
                $table->id();
                $table->string('question_category_id');
                $table->string('title');
                $table->string('description');
                $table->tinyInteger('is_published')->default(1);
                $table->tinyInteger('order');
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
        if (Schema::hasTable('questions')) {
            Schema::disableForeignKeyConstraints();
            Schema::dropIfExists('questions');
            Schema::enableForeignKeyConstraints();
        }
    }
}
