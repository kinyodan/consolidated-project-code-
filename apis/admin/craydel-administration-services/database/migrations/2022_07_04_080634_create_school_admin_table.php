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
  public function up(): void
  {
    if(!Schema::hasTable('school_admin')) {
      Schema::create('school_admin', function (Blueprint $table) {
        $table->id();
        $table->foreignId('school_id');
        $table->string('admin_name');
        $table->string('admin_email');
        $table->string('admin_phone');
        $table->string('admin_address');
        $table->string('admin_role');
        $table->tinyInteger('is_active')->default(1);
        $table->tinyInteger('is_deleted')->default(0);
        $table->string('created_by')->nullable();
        $table->string('updated_by')->nullable();
        $table->string('deleted_by')->nullable();
        $table->dateTime('created_at')->nullable();
        $table->dateTime('updated_at')->nullable();
        $table->dateTime('deleted_at')->nullable();
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
    
    if (Schema::hasTable('school_admin')) {
      Schema::disableForeignKeyConstraints();
      Schema::dropIfExists('school_admin');
      Schema::enableForeignKeyConstraints();
    }
  }
};
