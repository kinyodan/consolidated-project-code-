<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAcademicDisciplinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('academic_disciplines')){
            Schema::create('academic_disciplines', function (Blueprint $table) {
                $table->id();
                $table->string('discipline_code',50)->nullable(false)->unique();
                $table->string('discipline_name')->nullable(false)->unique();
                $table->string('temp_small_icon')->nullable();
                $table->string('discipline_small_icon')->nullable();
                $table->string('temp_large_icon')->nullable();
                $table->string('discipline_large_icon')->nullable();
                $table->text('seo_page_title')->nullable();
                $table->text('seo_page_description')->nullable();
                $table->text('seo_page_h1_title')->nullable();
                $table->text('seo_page_keywords')->nullable();
                $table->boolean('status')->default(1);
                $table->boolean('is_deleted')->default(0);
                $table->string('created_by')->nullable();
                $table->string('updated_by')->nullable();
                $table->string('approved_by')->nullable();
                $table->string('deleted_by')->nullable();
                $table->dateTime('created_at')->nullable();
                $table->dateTime('updated_at')->nullable();
                $table->dateTime('approved_at')->nullable();
                $table->dateTime('deleted_at')->nullable();
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
        if(Schema::hasTable('academic_disciplines')) {
            Schema::disableForeignKeyConstraints();
            Schema::dropIfExists('academic_disciplines');
            Schema::enableForeignKeyConstraints();
        }
    }
}
