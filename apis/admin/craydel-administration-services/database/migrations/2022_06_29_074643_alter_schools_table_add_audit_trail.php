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
        if (!Schema::hasColumn('schools', 'deleted_at')) {
          $table->dateTime('deleted_at')->nullable()->after('updated_at');
        }
        
        if (!Schema::hasColumn('schools', 'created_by')) {
          $table->string('created_by')->nullable()->after('deleted_at');
        }
        
        if (!Schema::hasColumn('schools', 'updated_by')) {
          $table->string('updated_by')->nullable()->after('created_by');
        }
        
        if (!Schema::hasColumn('schools', 'deleted_by')) {
          $table->string('deleted_by')->nullable()->after('updated_by');
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
        if (Schema::hasColumn('schools', 'deleted_at')) {
          $table->dropColumn('deleted_at');
        }
        
        if (Schema::hasColumn('schools', 'created_by')) {
          $table->dropColumn('created_by');
        }
        
        if (Schema::hasColumn('schools', 'updated_by')) {
          $table->dropColumn('updated_by');
        }
        
        if (Schema::hasColumn('schools', 'deleted_by')) {
          $table->dropColumn('deleted_by');
        }
      });
    }
  }
};
