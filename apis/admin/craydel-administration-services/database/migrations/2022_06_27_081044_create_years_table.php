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
      
  
      if(!Schema::hasTable('years')) {
        Schema::create('years', function (Blueprint $table) {
          $table->id();
          $table->string('year');
          $table->text('description')->nullable();
          $table->tinyInteger('is_active')->default(1);
          $table->tinyInteger('is_deleted')->default(0);
          $table->string('created_by')->nullable();
          $table->string('updated_by')->nullable();
          $table->string('deleted_by')->nullable();
          $table->dateTime('created_at')->nullable();
          $table->dateTime('updated_at')->nullable();
          $table->dateTime('deleted_at')->nullable();
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
      if(Schema::hasTable('years')) {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('years');
        Schema::enableForeignKeyConstraints();
      }
    }
};
