<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSelectedKeyphrasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('selected_key_phrases')) {
            Schema::create('selected_key_phrases', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('course_code');
                $table->text('phrases');
                $table->text('page_visits_associated_by_phrase')->nullable();
                $table->text('search_counts_by_phrase')->nullable();
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
        if (Schema::hasTable('selected_key_phrases')) {
            Schema::disableForeignKeyConstraints();
            Schema::dropIfExists('selected_key_phrases');
            Schema::enableForeignKeyConstraints();
        }
    }
}
