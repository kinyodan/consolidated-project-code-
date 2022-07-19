<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEducationTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('education_types')) {
            Schema::create('education_types', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('education_type_name')->unique();
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
        if (Schema::hasTable('education_types')) {
            Schema::disableForeignKeyConstraints();
            Schema::dropIfExists('education_types');
            Schema::enableForeignKeyConstraints();
        }
    }
}
