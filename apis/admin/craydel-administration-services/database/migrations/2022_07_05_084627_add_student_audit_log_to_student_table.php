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
      if(Schema::hasTable('students')) {
        Schema::table('students', function (Blueprint $table) {
          if (!Schema::hasColumn('students', 'is_deleted')) {
            $table->tinyInteger('is_deleted')->default(0)->after('is_account_activated');
          }
      
          if (!Schema::hasColumn('students', 'deleted_at')) {
            $table->dateTime('deleted_at')->nullable()->after('updated_at');
          }
      
          if (!Schema::hasColumn('students', 'created_by')) {
            $table->string('created_by')->nullable()->after('deleted_at');
          }
      
          if (!Schema::hasColumn('students', 'updated_by')) {
            $table->string('updated_by')->nullable()->after('created_by');
          }
      
          if (!Schema::hasColumn('students', 'deleted_by')) {
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
      if(Schema::hasTable('students')) {
        Schema::table('students', function (Blueprint $table) {
          if (Schema::hasColumn('students', 'is_deleted')) {
            $table->dropColumn('is_deleted');
          }
      
          if (Schema::hasColumn('students', 'deleted_at')) {
            $table->dropColumn('deleted_at');
          }
      
          if (Schema::hasColumn('students', 'created_by')) {
            $table->dropColumn('created_by');
          }
      
          if (Schema::hasColumn('students', 'updated_by')) {
            $table->dropColumn('updated_by');
          }
      
          if (Schema::hasColumn('students', 'deleted_by')) {
            $table->dropColumn('deleted_by');
          }
        });
      }
    }
};
