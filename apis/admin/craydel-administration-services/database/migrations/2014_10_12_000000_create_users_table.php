<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up(): void
  {
    if (!Schema::hasTable('users')) {
      Schema::create('users', function (Blueprint $table) {
        $table->bigIncrements('id');
        $table->string('email')->unique('unique_email_address');
        $table->integer('country_id')->nullable();
        $table->string('country_code', 10)->nullable();
        $table->string('user_code', 40)->nullable();
        $table->string('user_provider')->nullable();
        $table->string('default_language', 10)->default('en');
        $table->string('default_currency_name', 50)->nullable();
        $table->string('default_currency_code', 10)->nullable();
        $table->string('timezone', 20)->nullable();
        $table->string('full_name', 50)->nullable();
        $table->string('first_name', 50)->nullable();
        $table->string('last_name', 50)->nullable();
        $table->string('display_name', 50)->nullable();
        $table->string('acronym', 10)->nullable();
        $table->string('gender')->nullable();
        $table->text('profile_picture_url')->nullable();
        $table->string('mobile_number', 20)->nullable();
        $table->text('permissions')->nullable();
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
    if (Schema::hasTable('users')) {
      Schema::disableForeignKeyConstraints();
      Schema::dropIfExists('users');
      Schema::enableForeignKeyConstraints();
    }
  }
};
