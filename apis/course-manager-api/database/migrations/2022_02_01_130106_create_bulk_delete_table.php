<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBulkDeleteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        if(!Schema::hasTable('bulk_action')) {
            Schema::create('bulk_action', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('batch_no');
                $table->string('course_code');
                $table->string('action_type');
                $table->tinyInteger('is_processed')->default(0);
                $table->tinyInteger('picked_for_processing')->default(0);
                $table->text('processing_error')->nullable();
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
        Schema::dropIfExists('bulk_delete');
    }
}
