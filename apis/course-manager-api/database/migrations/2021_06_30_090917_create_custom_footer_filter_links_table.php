<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomFooterFilterLinksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('custom_footer_filter_links')) {
            Schema::create('custom_footer_filter_links', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('filter_type');
                $table->string('title');
                $table->longText('attributes');
                $table->tinyInteger('is_active')->default(1);
                $table->dateTime('created_at')->nullable();
                $table->dateTime('updated_at')->nullable();
                $table->dateTime('deleted_at')->nullable();
                $table->string('created_by')->nullable();
                $table->string('updated_by')->nullable();
                $table->string('deleted_by')->nullable();
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
        if(Schema::hasTable('custom_footer_filter_links')){
            Schema::disableForeignKeyConstraints();
            Schema::dropIfExists('custom_footer_filter_links');
            Schema::enableForeignKeyConstraints();
        }
    }
}
