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
      if (Schema::hasTable('schools')) {
        Schema::table('schools', function (Blueprint $table) {
          if (!Schema::hasColumn('schools', 'parent_school_id')) {
            $table->bigInteger('parent_school_id')->nullable()->default(0);
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
          if (Schema::hasColumn('schools', 'parent_school_id')) {
            $table->dropColumn('parent_school_id');
          }
        });
      }
    }
};
