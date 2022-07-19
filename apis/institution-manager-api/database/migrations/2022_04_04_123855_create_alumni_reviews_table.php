<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAlumniReviewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('alumni_reviews')){
            Schema::create('alumni_reviews', function (Blueprint $table) {
                $table->charset = 'utf8mb4';
                $table->collation = 'utf8mb4_unicode_ci';
                $table->increments('id');
                $table->string('institution_alumni_id');
                $table->string('institution_code');
                $table->text('reviews');
                $table->string('up_vote');
                $table->string('down_vote');
                $table->integer('flagged');
                $table->tinyInteger('is_published')->default(1);
                $table->text('un_published_reasons')->nullable();
                $table->tinyInteger('is_deleted')->default(0);
                $table->text('deleted_reasons')->nullable();
                $table->integer('created_by')->nullable();
                $table->integer('updated_by')->nullable();
                $table->integer('deleted_by')->nullable();
                $table->dateTimeTz('deleted_at')->nullable();
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
        if(Schema::hasTable('alumni_reviews')){
            Schema::disableForeignKeyConstraints();
            Schema::dropIfExists('alumni_reviews');
            Schema::enableForeignKeyConstraints();
        }


    }
}
