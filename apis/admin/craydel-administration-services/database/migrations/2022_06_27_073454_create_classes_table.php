<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void
  {
    if (!Schema::hasTable('classes')) {
      Schema::create('classes', function (Blueprint $table) {
        $table->id();
        $table->string('class_name', 500);
        $table->string('class_name_slug', 500);
        $table->BigInteger('school_id')->default(1);
        $table->boolean('status')->default(1);
        $table->string('deleted_at', 500);
        $table->string('created_by', 500);
        $table->string('updated_by', 500);
        $table->string('deleted_by', 500);
        $table->timestamps();
        
        
      });
    }
  }
  
 
  public function down(): void
  {
    if (Schema::hasTable('classes')) {
      Schema::disableForeignKeyConstraints();
      Schema::dropIfExists('classes');
      Schema::enableForeignKeyConstraints();
    }
  }
};
