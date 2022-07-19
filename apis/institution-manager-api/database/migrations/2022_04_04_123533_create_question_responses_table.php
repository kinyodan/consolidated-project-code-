<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuestionResponsesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('question_responses')) {
            Schema::create('question_responses', function (Blueprint $table) {
                $table->id();
                $table->string('question_category_id');
                $table->string('question_id');
                $table->string('institution_code');
                $table->string('institution_alumni_id');
                $table->float('scores');
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
        if (Schema::hasTable('question_responses')) {
            Schema::disableForeignKeyConstraints();
            Schema::dropIfExists('question_responses');
            Schema::enableForeignKeyConstraints();
        }
    }
}
