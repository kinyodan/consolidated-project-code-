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
  public function up()
  {
    if (Schema::hasTable('schools')) {
      Schema::table('schools', function (Blueprint $table) {
        if (!Schema::hasColumn('schools', 'temp_school_logo_url')) {
          $table->text('temp_school_logo_url')->nullable();
        }
        if (!Schema::hasColumn('schools', 'temp_school_logo_url_error')) {
          $table->text('temp_school_logo_url_error')->nullable();
        }
        if (!Schema::hasColumn('schools', 'temp_school_inverse_logo_url')) {
          $table->text('temp_school_inverse_logo_url')->nullable();
        }
        if (!Schema::hasColumn('schools', 'temp_school_inverse_logo_url_error')) {
          $table->text('temp_school_inverse_logo_url_error')->nullable();
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
        if (Schema::hasColumn('schools', 'temp_school_logo_url')) {
          $table->dropColumn('temp_school_logo_url');
        }
        if (Schema::hasColumn('schools', 'temp_school_logo_url_error')) {
          $table->dropColumn('temp_school_logo_url_error');
        }
        if (Schema::hasColumn('schools', 'temp_school_inverse_logo_url')) {
          $table->dropColumn('temp_school_inverse_logo_url');
        }
        if (Schema::hasColumn('schools', 'temp_school_inverse_logo_url_error')) {
          $table->dropColumn('temp_school_inverse_logo_url_error');
        }
      });
    }
  }
};
