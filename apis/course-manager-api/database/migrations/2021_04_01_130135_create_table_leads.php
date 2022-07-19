<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableLeads extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('leads')) {
            Schema::create('leads', function (Blueprint $table) {
                $table->charset = 'utf8mb4';
                $table->collation = 'utf8mb4_unicode_ci';
                $table->bigIncrements('id');
                $table->string('course_code', 50);
                $table->string('mobile_number', 50);
                $table->string('email', 50)->nullable();
                $table->string('city', 50)->nullable();
                $table->string('country', 50)->nullable();
                $table->string('first_name', 50)->nullable();
                $table->string('last_name', 50)->nullable();
                $table->text('description')->nullable();
                $table->text('course_name')->nullable();
                $table->text('institution_name')->nullable();
                $table->text('page_url')->nullable();
                $table->text('referrer_url')->nullable();
                $table->string('default_lead_source', 100)->nullable();
                $table->string('lead_status', 100)->nullable();
                $table->tinyInteger('is_processed')->default(0);
                $table->tinyInteger('is_picked_for_processing')->default(0);
                $table->dateTime('time_picked_for_processing')->nullable();
                $table->string('lms_provider')->nullable();
                $table->string('lms_provider_lead_id')->nullable();
                $table->text('lms_provider_error_message')->nullable();
                $table->dateTime('created_at')->nullable();
                $table->dateTime('updated_at')->nullable();
                $table->unique(['course_code', 'mobile_number'], 'unique_lead');
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
        if(Schema::hasTable('leads')){
            Schema::disableForeignKeyConstraints();
            Schema::dropIfExists('leads');
            Schema::enableForeignKeyConstraints();
        }
    }
}
