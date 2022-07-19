<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CountriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('countries')){
            Schema::create('countries', function (Blueprint $table) {
                $table->charset = 'utf8mb4';
                $table->collation = 'utf8mb4_unicode_ci';
                $table->increments('id');
                $table->string('continent');
                $table->string('geographical_region');
                $table->string('iso_code');
                $table->string('iso3_code');
                $table->string('timezone');
                $table->string('number_code');
                $table->string('phone_code');
                $table->string('currency_code')->nullable();
                $table->string('currency_name')->nullable();
                $table->string('name')->index('index_country_name');
                $table->string('slug')->unique();
                $table->tinyInteger('is_blocked')->default(0);
                $table->tinyInteger('is_deleted')->default(0);
                $table->integer('created_by')->nullable();
                $table->integer('updated_by')->nullable();
                $table->integer('deleted_by')->nullable();
                $table->integer('deactivated_by')->nullable();
                $table->dateTimeTz('deleted_at')->nullable();
                $table->dateTimeTz('deactivated_at')->nullable();
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
        if(Schema::hasTable('countries')){
            Schema::disableForeignKeyConstraints();
            Schema::dropIfExists('countries');
            Schema::enableForeignKeyConstraints();
        }
    }
}
