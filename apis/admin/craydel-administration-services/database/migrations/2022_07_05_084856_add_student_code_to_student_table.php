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
          if(!Schema::hasColumn('students','student_code')) {
            $table->string('student_code', 20)->after('id')->nullable()->unique('unique_student_code');
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
          if(Schema::hasColumn('students','student_code')) {
            $table->dropColumn('student_code');
          }
        });
      }
    }
};
