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
      if(!Schema::hasTable('students')) {
        Schema::create('students', function (Blueprint $table) {
          $table->id();
          $table->unsignedBigInteger('craydel_user_id')->nullable();
          $table->foreignId('school_id');
          $table->foreignId('stream_id');
          $table->string('student_name', 500);
          $table->string('student_email', 500)->unique();
          $table->string('student_phone', 500)->nullable();
          $table->text('student_address')->nullable();
          $table->boolean('is_craydel_account_created')->default(0);
          $table->boolean('is_invite_sent')->default(0);
          $table->boolean('is_account_activated')->default(0);
          $table->boolean('has_done_career_counselling')->default(0);
          $table->boolean('has_applied_for_course')->default(0);
          $table->timestamps();
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
        Schema::dropIfExists('students');
    }
};
