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
    if (Schema::hasTable('schools')) {
      Schema::table('schools', function (Blueprint $table) {
        if (!Schema::hasColumn('schools', 'discount_type')) {
          $table->enum('discount_type', ['percentage', 'flat_amount']);
        }
        if (!Schema::hasColumn('schools', 'discount_value')) {
          $table->unsignedInteger('discount_value');
        }
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
    if (Schema::hasTable('schools')) {
      Schema::table('schools', function (Blueprint $table) {
        if (Schema::hasColumn('schools', 'discount_type')) {
          $table->dropColumn('discount_type');
        }
        if (Schema::hasColumn('schools', 'discount_value')) {
          $table->dropColumn('discount_value');
        }
      });
    }
  }
};
