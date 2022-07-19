<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class InstitutionType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('institution_types')) {
            Schema::create('institution_types', function (Blueprint $table) {
                $table->charset = 'utf8mb4';
                $table->collation = 'utf8mb4_unicode_ci';
                $table->integerIncrements('id');
                $table->string('name')->unique();
                $table->string('slug')->unique();
                $table->text('description')->nullable();
                $table->tinyInteger('is_deleted')->default(0);
                $table->tinyInteger('is_blocked')->default(0);
                $table->string('created_by')->nullable();
                $table->string('updated_by')->nullable();
                $table->dateTime('created_at')->nullable();
                $table->dateTime('updated_at')->nullable();
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
        if(Schema::hasTable('institution_types')){
            Schema::disableForeignKeyConstraints();
            Schema::dropIfExists('institution_types');
            Schema::enableForeignKeyConstraints();
        }
    }
}
