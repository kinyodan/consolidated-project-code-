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
        if (!Schema::hasColumn('schools', 'is_deleted')) {
          $table->tinyInteger('is_deleted')->default(0);
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
        if (Schema::hasColumn('schools', 'is_deleted')) {
          $table->dropColumn('is_deleted');
        }
      });
    }
  }
};
