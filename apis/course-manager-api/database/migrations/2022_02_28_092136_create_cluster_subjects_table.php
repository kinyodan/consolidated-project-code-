<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClusterSubjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('cluster_subjects')) {
            Schema::create('cluster_subjects', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->integer('cluster_id')->unsigned();
                $table->integer('subject_id')->unsigned();
                $table->integer('education_type_id')->unsigned();
                $table->integer('country_id')->unsigned();
                $table->tinyInteger('is_primary')->default(1);
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
        if (Schema::hasTable('cluster_subjects')) {
            Schema::disableForeignKeyConstraints();
            Schema::dropIfExists('cluster_subjects');
            Schema::enableForeignKeyConstraints();
        }
    }
}
