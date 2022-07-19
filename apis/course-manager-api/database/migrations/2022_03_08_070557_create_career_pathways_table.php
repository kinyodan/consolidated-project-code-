<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCareerPathwaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('career_pathways')) {
            Schema::create('career_pathways', function (Blueprint $table) {
                $table->id();
                $table->string('career_pathway_name');
                $table->string('career_pathway_slug')->unique();
                $table->text('career_pathway_description')->nullable();
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
        if(Schema::hasTable('career_pathways')){
            Schema::disableForeignKeyConstraints();
            Schema::dropIfExists('career_pathways');
            Schema::enableForeignKeyConstraints();
        }
    }
}
