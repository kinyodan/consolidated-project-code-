<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClustersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('clusters')) {
            Schema::create('clusters', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('cluster_name')->unique();
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
        if (Schema::hasTable('clusters')) {
            Schema::disableForeignKeyConstraints();
            Schema::dropIfExists('clusters');
            Schema::enableForeignKeyConstraints();
        }
    }
}
