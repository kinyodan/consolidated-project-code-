<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLearningModesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('learning_modes')){
            Schema::create('learning_modes', function (Blueprint $table) {
                $table->integerIncrements('id');
                $table->string('name');
                $table->string('slug')->unique();
                $table->tinyInteger('is_deleted')->default(0);
                $table->tinyInteger('is_blocked')->default(0);
                $table->dateTime('created_at')->nullable();
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
        if(Schema::hasTable('learning_modes')){
            Schema::disableForeignKeyConstraints();
            Schema::dropIfExists('learning_modes');
            Schema::enableForeignKeyConstraints();
        }
    }
}
