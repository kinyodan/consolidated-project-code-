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
          if(!Schema::hasColumn('students','student_image')) {
            $table->text("student_image")->nullable()->after('student_lead_status');
          }
      
          if(!Schema::hasColumn('students','date_of_birth')) {
            $table->date("date_of_birth")->nullable()->after('student_image');
          }
      
          if(!Schema::hasColumn('students','gender')) {
            $table->string("gender", 40)->nullable()->after('date_of_birth');
          }
      
          if(!Schema::hasColumn('students','nationality')) {
            $table->string("nationality", 40)->nullable()->after('gender');
          }
      
          if(!Schema::hasColumn('students','curriculum_id')) {
            $table->unsignedBigInteger("curriculum_id")->nullable()->after('nationality');
          }
      
          if(!Schema::hasColumn('students','date_enrolled')) {
            $table->date("date_enrolled")->nullable()->after('curriculum_id');
          }
      
          if(!Schema::hasColumn('students','year_id')) {
            $table->unsignedBigInteger("year_id")->nullable()->after('date_enrolled');
          }
      
          if(!Schema::hasColumn('students','guardian_profile_photo')) {
            $table->text("guardian_profile_photo")->nullable()->after('year_id');
          }
      
          if(!Schema::hasColumn('students','guardian_name')) {
            $table->text("guardian_name")->nullable()->after('guardian_profile_photo');
          }
      
          if(!Schema::hasColumn('students','guardian_mobile_number')) {
            $table->string("guardian_mobile_number", 50)->nullable()->after('guardian_name');
          }
      
          if(!Schema::hasColumn('students','guardian_email')) {
            $table->string("guardian_email", 50)->nullable()->after('guardian_mobile_number');
          }
      
          if(!Schema::hasColumn('students','guardian_student_relationship')) {
            $table->string("guardian_student_relationship")->nullable()->after('guardian_email');
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
          if(Schema::hasColumn('students','student_image')) {
            $table->dropColumn('student_image');
          }
      
          if(Schema::hasColumn('students','date_of_birth')) {
            $table->dropColumn('date_of_birth');
          }
      
          if(Schema::hasColumn('students','gender')) {
            $table->dropColumn('gender');
          }
      
          if(Schema::hasColumn('students','nationality')) {
            $table->dropColumn('nationality');
          }
      
          if(Schema::hasColumn('students','curriculum_id')) {
            $table->dropColumn('curriculum_id');
          }
      
          if(Schema::hasColumn('students','date_enrolled')) {
            $table->dropColumn('date_enrolled');
          }
      
          if(Schema::hasColumn('students','year_id')) {
            $table->dropColumn('year_id');
          }
      
          if(Schema::hasColumn('students','guardian_profile_photo')) {
            $table->dropColumn('guardian_profile_photo');
          }
      
          if(Schema::hasColumn('students','guardian_name')) {
            $table->dropColumn('guardian_name');
          }
      
          if(Schema::hasColumn('students','guardian_mobile_number')) {
            $table->dropColumn('guardian_mobile_number');
          }
      
          if(Schema::hasColumn('students','guardian_email')) {
            $table->dropColumn('guardian_email');
          }
      
          if(Schema::hasColumn('students','guardian_student_relationship')) {
            $table->dropColumn('guardian_student_relationship');
          }
        });
      }
    }
};
