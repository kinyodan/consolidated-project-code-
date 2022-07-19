<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCountryIntakes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('country_intakes')) {
            Schema::create('country_intakes', function (Blueprint $table) {
                $table->id();
                $table->string('country_name');
                $table->string('country_code');
                $table->string('month_name');
                $table->string('created_by')->nullable();
                $table->string('updated_by')->nullable();
                $table->string('deleted_by')->nullable();
                $table->dateTime('created_at')->nullable();
                $table->dateTime('update_at')->nullable();
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
        if(Schema::hasTable('country_intakes')){
            Schema::disableForeignKeyConstraints();
            Schema::dropIfExists('country_intakes');
            Schema::enableForeignKeyConstraints();
        }
    }
}
