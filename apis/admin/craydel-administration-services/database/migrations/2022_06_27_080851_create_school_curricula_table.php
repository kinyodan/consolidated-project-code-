<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
      if(!Schema::hasTable('school_curriculum')) {
        Schema::create('school_curriculum', function (Blueprint $table) {
          $table->unsignedBigInteger('school_id');
          $table->unsignedBigInteger('curriculum_id', '');
          $table->unique(['school_id', 'curriculum_id'], 'unique_school_curriculum');
      
          $table->foreign('school_id')->references('id')->on('schools');
          $table->foreign('curriculum_id')->references('id')->on('curriculums');
        });
      }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
      if(Schema::hasTable('school_curriculum')) {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('school_curriculum');
        Schema::enableForeignKeyConstraints();
      }
    }
};
