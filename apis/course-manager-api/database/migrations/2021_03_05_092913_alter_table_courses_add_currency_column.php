<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableCoursesAddCurrencyColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(Schema::hasTable('courses')) {
            Schema::table('courses', function (Blueprint $table) {
                if(!Schema::hasColumn('courses','currency')){
                    $table->string('currency',50)->nullable();
                }
                if(!Schema::hasColumn('courses','fees_structure_url')){
                    $table->string('fees_structure_url',255)->nullable();
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
        if(Schema::hasTable('courses')) {
            Schema::table('courses', function (Blueprint $table) {
                if(Schema::hasColumn('courses','currency')){
                    $table->dropColumn('currency');
                }
                if(Schema::hasColumn('courses','fees_structure_url')){
                    $table->dropColumn('fees_structure_url');
                }
            });
        }
    }
}
