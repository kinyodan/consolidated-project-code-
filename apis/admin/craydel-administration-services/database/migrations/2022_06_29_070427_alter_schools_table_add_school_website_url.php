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
          if (!Schema::hasColumn('schools', 'school_website_url')) {
            $table->text('school_website_url')->nullable()->after('school_physical_address');
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
          if (Schema::hasColumn('schools', 'school_website_url')) {
            $table->dropColumn('school_website_url');
          }
        });
      }
    }
};
