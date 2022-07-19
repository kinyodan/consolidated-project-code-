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
          if(!Schema::hasColumn('students','is_marked_for_downstream_deletion')) {
            $table->tinyInteger('is_marked_for_downstream_deletion')->default(0)->after('is_deleted');
          }
      
          if(!Schema::hasColumn('students','is_downstream_deleted')) {
            $table->tinyInteger('is_downstream_deleted')->default(0)->after('is_marked_for_downstream_deletion');
          }
      
          if(!Schema::hasColumn('students','is_picked_for_downstream_deletion')) {
            $table->tinyInteger('is_picked_for_downstream_deletion')->default(0)->after('is_downstream_deleted');
          }
      
          if(!Schema::hasColumn('students','date_picked_for_downstream_deletion')) {
            $table->dateTime('date_picked_for_downstream_deletion')->nullable()->after('is_picked_for_downstream_deletion');
          }
      
          if(!Schema::hasColumn('students','downstream_deletion_responses')) {
            $table->longText('downstream_deletion_responses')->nullable()->after('date_picked_for_downstream_deletion');
          }
      
          if(!Schema::hasColumn('students','downstream_deletion_errors')) {
            $table->longText('downstream_deletion_errors')->nullable()->after('downstream_deletion_responses');
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
          if(Schema::hasColumn('students','is_marked_for_downstream_deletion')) {
            $table->dropColumn('is_marked_for_downstream_deletion');
          }
      
          if(Schema::hasColumn('students','is_downstream_deleted')) {
            $table->dropColumn('is_downstream_deleted');
          }
      
          if(Schema::hasColumn('students','is_picked_for_downstream_deletion')) {
            $table->dropColumn('is_picked_for_downstream_deletion');
          }
      
          if(Schema::hasColumn('students','date_picked_for_downstream_deletion')) {
            $table->dropColumn('date_picked_for_downstream_deletion');
          }
      
          if(Schema::hasColumn('students','downstream_deletion_responses')) {
            $table->dropColumn('downstream_deletion_responses');
          }
      
          if(Schema::hasColumn('students','downstream_deletion_errors')) {
            $table->dropColumn('downstream_deletion_errors');
          }
        });
      }
    }
};
