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
        if (!Schema::hasColumn('schools', 'school_code')) {
          $table->string('school_code', 20)->after('id')->nullable()->unique('unique_school_code');
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
        if (Schema::hasColumn('schools', 'school_code')) {
          $table->dropColumn('school_code');
        }
      });
    }
  }
};
