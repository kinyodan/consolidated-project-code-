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
          if (Schema::hasColumn('schools', 'discount_value')) {
            $table->integer('discount_value')->nullable()->change();
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
      Schema::table('schools', function (Blueprint $table) {
        if (Schema::hasColumn('schools', 'discount_value')) {
          $table->integer('discount_value')->nullable(false)->change();
        }
      });
    }
};
