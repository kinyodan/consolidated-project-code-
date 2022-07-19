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
      if(!Schema::hasTable('countries')) {
        Schema::create('countries', function (Blueprint $table) {
          $table->charset = 'utf8mb4';
          $table->collation = 'utf8mb4_unicode_ci';
          $table->id();
          $table->string('code')->unique();
          $table->string('name');
          $table->string('dial_code');
          $table->string('currency_name');
          $table->string('currency_symbol');
          $table->string('currency_code');
          $table->string('timezone');
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
      if (Schema::hasTable('countries')) {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('countries');
        Schema::enableForeignKeyConstraints();
      }
      
    }
};
