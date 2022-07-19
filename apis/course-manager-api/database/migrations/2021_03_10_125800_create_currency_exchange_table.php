<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCurrencyExchangeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('currency_exchange')) {
            Schema::create('currency_exchange', function (Blueprint $table) {
                $table->id();
                $table->string('base_currency');
                $table->string('secondary_currency');
                $table->float('exchange_rate', 14,2);
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
        if(Schema::hasTable('currency_exchange')) {
            Schema::dropIfExists('currency_exchange');
        }
    }
}
