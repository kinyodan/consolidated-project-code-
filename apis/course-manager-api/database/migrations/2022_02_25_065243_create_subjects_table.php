<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('subjects')) {
            Schema::create('subjects', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('subject_name')->unique();
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
        if (Schema::hasTable('subjects')) {
            Schema::disableForeignKeyConstraints();
            Schema::dropIfExists('subjects');
            Schema::enableForeignKeyConstraints();
        }
    }
}
