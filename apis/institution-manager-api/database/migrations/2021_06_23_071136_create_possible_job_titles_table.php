<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePossibleJobTitlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('possible_job_titles')) {
            Schema::create('possible_job_titles', function (Blueprint $table) {
                $table->charset = 'utf8mb4';
                $table->collation = 'utf8mb4_unicode_ci';
                $table->bigIncrements('id');
                $table->unsignedBigInteger('job_title_category_id');
                $table->string('language')->default('en');
                $table->string('job_title_slug');
                $table->string('job_title');
                $table->longText('description')->nullable();
                $table->tinyInteger('is_active')->default(1);
                $table->tinyInteger('is_deleted')->default(0);
                $table->string('created_by')->nullable();
                $table->string('updated_by')->nullable();
                $table->string('deleted_by')->nullable();
                $table->dateTime('created_at')->nullable();
                $table->dateTime('updated_at')->nullable();
                $table->dateTime('deleted_at')->nullable();
                $table->unique(['job_title_category_id', 'language', 'job_title_slug'], 'unique_job_title');
                $table->foreign('job_title_category_id')
                    ->references('id')
                    ->on('possible_job_title_categories')
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
        if(Schema::hasTable('possible_job_titles')){
            Schema::disableForeignKeyConstraints();
            Schema::dropIfExists('possible_job_titles');
            Schema::enableForeignKeyConstraints();
        }
    }
}
