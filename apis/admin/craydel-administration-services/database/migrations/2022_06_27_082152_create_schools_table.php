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
      if(!Schema::hasTable('schools')) {
        Schema::create('schools', function (Blueprint $table) {
          $table->id();
          $table->string('school_name', 500);
          $table->string('school_email', 500)->unique();
          $table->string('school_phone', 500)->unique();
          $table->text('school_address');
          $table->text('school_physical_address')->nullable();
          $table->boolean('is_verified')->default(0);
          $table->boolean('status')->default(0);
          $table->timestamps();
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
      Schema::dropIfExists('schools');
    }
};
