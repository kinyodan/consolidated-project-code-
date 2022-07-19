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
          if (!Schema::hasColumn('students', 'student_phone_country_code')) {
            $table->string('student_phone_country_code', 20)->nullable()->after('student_phone');
          }
      
          if (!Schema::hasColumn('students', 'guardian_mobile_number_country_code')) {
            $table->string('guardian_mobile_number_country_code', 20)->nullable()->after('guardian_mobile_number');
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
          if (Schema::hasColumn('students', 'student_phone_country_code')) {
            $table->dropColumn('student_phone_country_code');
          }
      
          if (Schema::hasColumn('students', 'guardian_mobile_number_country_code')) {
            $table->dropColumn('guardian_mobile_number_country_code');
          }
        });
      }
    }
};
