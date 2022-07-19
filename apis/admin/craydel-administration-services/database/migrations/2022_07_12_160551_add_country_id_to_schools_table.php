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
    public function up()
    {
      if (Schema::hasTable('schools')) {
        Schema::table('schools', function (Blueprint $table) {
          if (!Schema::hasColumn('schools', 'country_id')) {
            $table->integer('country_id')->default(0)->after('country_code');
          }
        });
      }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      if (Schema::hasTable('schools')) {
        Schema::table('schools', function (Blueprint $table) {
          if (Schema::hasColumn('schools', 'country_id')) {
            $table->dropColumn('country_id');
          }
        });
      }
    }
};
