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
    if (Schema::hasTable('streams')) {
      Schema::table('streams', function (Blueprint $table) {
        if (!Schema::hasColumn('streams', 'stream_name')) {
          $table->string('stream_name')->nullable()->after('id');
        }
        if (!Schema::hasColumn('streams', 'stream_name_slug')) {
          $table->string('stream_name_slug')->nullable()->after('stream_name');
        }
        if (!Schema::hasColumn('streams', 'school_id')) {
          $table->bigInteger('school_id')->default(1)->after('stream_name');
        }
        if (!Schema::hasColumn('streams', 'status')) {
          $table->bigInteger('status')->default(1)->after('school_id');
        }
        if (!Schema::hasColumn('streams', 'is_deleted')) {
          $table->tinyInteger('is_deleted')->default(0)->after('status');
        }
        if (!Schema::hasColumn('streams', 'deleted_at')) {
          $table->string('deleted_at')->nullable()->after('is_deleted');
        }
        if (!Schema::hasColumn('streams', 'created_by')) {
          $table->string('created_by')->nullable()->after('deleted_at');
        }
        if (!Schema::hasColumn('streams', 'updated_by')) {
          $table->string('updated_by')->nullable()->after('created_by');
        }
        if (!Schema::hasColumn('streams', 'deleted_by')) {
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
    if (Schema::hasTable('streams')) {
      Schema::table('streams', function (Blueprint $table) {
        if (Schema::hasColumn('streams', 'stream_name')) {
          $table->dropColumn('stream_name');
        }
        
        if (Schema::hasColumn('streams', 'stream_name_slug')) {
          $table->dropColumn('stream_name_slug');
        }
        
        if (Schema::hasColumn('streams', 'school_id')) {
          $table->dropColumn('school_id');
        }
        
        if (Schema::hasColumn('streams', 'status')) {
          $table->dropColumn('status');
        }
        if (Schema::hasColumn('streams', 'is_deleted')) {
          $table->dropColumn('is_deleted');
        }
        if (Schema::hasColumn('streams', 'deleted_at')) {
          $table->dropColumn('deleted_at');
        }
        
        if (Schema::hasColumn('streams', 'created_by')) {
          $table->dropColumn('created_by');
        }
        
        if (Schema::hasColumn('streams', 'updated_by')) {
          $table->dropColumn('updated_by');
        }
        
        if (Schema::hasColumn('streams', 'deleted_by')) {
          $table->dropColumn('deleted_by');
        }
      });
    }
  }
};
