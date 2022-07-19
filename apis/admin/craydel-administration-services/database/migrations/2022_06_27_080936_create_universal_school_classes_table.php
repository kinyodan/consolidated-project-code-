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
      if(!Schema::hasTable('universal_school_classes')) {
        Schema::create('universal_school_classes', function (Blueprint $table) {
          $table->id();
          $table->unsignedBigInteger('curriculum_id');
          $table->string('class_name_slug');
          $table->string('class_name')->nullable();
          $table->tinyInteger('is_active')->default(1);
          $table->tinyInteger('is_deleted')->default(0);
          $table->string('created_by')->nullable();
          $table->string('updated_by')->nullable();
          $table->string('deleted_by')->nullable();
          $table->dateTime('created_at')->nullable();
          $table->dateTime('updated_at')->nullable();
          $table->dateTime('deleted_at')->nullable();
      
          $table->foreign('curriculum_id')->references('id')->on('curriculums');
          $table->unique(['curriculum_id', 'class_name_slug'], 'unique_class_name_slug');
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
      if(Schema::hasTable('universal_school_classes')) {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('universal_school_classes');
        Schema::enableForeignKeyConstraints();
      }
    }
};
