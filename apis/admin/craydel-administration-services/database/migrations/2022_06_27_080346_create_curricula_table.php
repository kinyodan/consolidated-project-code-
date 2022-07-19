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
      if(!Schema::hasTable('curriculums')) {
        Schema::create('curriculums', function (Blueprint $table) {
          $table->id();
          $table->string('curriculum_slug');
          $table->string('country_code')->nullable();
          $table->string('curriculum_name')->nullable();
          $table->string('curriculum_code')->unique('unique_curriculum_code');
          $table->tinyInteger('is_global')->default(0);
          $table->tinyInteger('is_active')->default(1);
          $table->tinyInteger('is_deleted')->default(0);
          $table->string('created_by')->nullable();
          $table->string('updated_by')->nullable();
          $table->string('deleted_by')->nullable();
          $table->dateTime('created_at')->nullable();
          $table->dateTime('updated_at')->nullable();
          $table->dateTime('deleted_at')->nullable();
          $table->unique(['country_code', 'curriculum_slug']);
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
      if(Schema::hasTable('curriculums')) {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('curriculums');
        Schema::enableForeignKeyConstraints();
      }
    }
};
