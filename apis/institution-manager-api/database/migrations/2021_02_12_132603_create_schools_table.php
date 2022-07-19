<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSchoolsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('schools')){
            Schema::create('schools', function (Blueprint $table) {
                $table->charset = 'utf8mb4';
                $table->collation = 'utf8mb4_unicode_ci';
                $table->bigIncrements('id');
                $table->string('country_code');
                $table->string('institution_code', 50);
                $table->string('college_code', 50);
                $table->string('school_code', 50)->nullable()->unique();
                $table->string('school_name_slug', 50);
                $table->string('school_name')->nullable();
                $table->text('description')->nullable();
                $table->text('profile_details')->nullable();
                $table->tinyInteger('is_deleted')->default(0);
                $table->tinyInteger('is_active')->default(1);
                $table->string('created_by')->nullable();
                $table->string('updated_by')->nullable();
                $table->string('approved_by')->nullable();
                $table->string('deleted_by')->nullable();
                $table->dateTimeTz('created_at')->nullable();
                $table->dateTimeTz('updated_at')->nullable();
                $table->dateTimeTz('approved_at')->nullable();
                $table->dateTimeTz('deleted_at')->nullable();
                $table->unique(['country_code', 'institution_code', 'college_code', 'school_name_slug'], 'unique_school');
                $table->foreign('institution_code')
                    ->references('institution_code')
                    ->on('institutions')
                    ->onDelete('cascade');

                $table->foreign('college_code')
                    ->references('college_code')
                    ->on('colleges')
                    ->onDelete('cascade');
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
        Schema::dropIfExists('schools');
    }
}
