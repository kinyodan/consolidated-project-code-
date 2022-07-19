<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDisplaySubjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('display_subjects')) {
            Schema::create('display_subjects', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->text('education_type_id');
                $table->text('country_id');
                $table->text('academic_disciplines_id');
                $table->text('subject_title');
                $table->text('subject_title_description');
                $table->text('display_order');
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
        if (Schema::hasTable('display_subjects')) {
            Schema::disableForeignKeyConstraints();
            Schema::dropIfExists('display_subjects');
            Schema::enableForeignKeyConstraints();
        }
    }
}
