<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddImagesToCareerPathwaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(Schema::hasTable('career_pathways')) {
            Schema::table('career_pathways', function (Blueprint $table) {
                if (!Schema::hasColumn('career_pathways', 'image')) {
                    $table->text('image')
                        ->after('career_pathway_description')
                        ->nullable();
                }
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
        if(Schema::hasTable('career_pathways')) {
            Schema::table('career_pathways', function (Blueprint $table) {
                if (Schema::hasColumn('career_pathways', 'image')) {
                    Schema::disableForeignKeyConstraints();
                    $table->dropColumn('image');
                    Schema::enableForeignKeyConstraints();
                }
            });
        }
    }
}
