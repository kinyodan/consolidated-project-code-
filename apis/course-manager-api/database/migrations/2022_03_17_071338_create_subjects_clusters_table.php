<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubjectsClustersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {


        if (!Schema::hasTable('subjects_clusters')) {
            Schema::create('subjects_clusters', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->text('education_type_id');
                $table->text('subject_id');
                $table->text('cluster_id');
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
        if (Schema::hasTable('subjects_clusters')) {
            Schema::disableForeignKeyConstraints();
            Schema::dropIfExists('subjects_clusters');
            Schema::enableForeignKeyConstraints();
        }
    }
}
