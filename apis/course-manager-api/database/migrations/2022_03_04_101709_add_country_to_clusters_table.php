<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCountryToClustersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(Schema::hasTable('clusters')) {
            Schema::table('clusters', function (Blueprint $table) {
                if (!Schema::hasColumn('clusters', 'country_id')) {
                    $table->Integer('country_id')
                        ->after('cluster_name');
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

        if(Schema::hasTable('clusters')) {
            Schema::table('clusters', function (Blueprint $table) {
                if (Schema::hasColumn('clusters', 'country_id')) {
                    Schema::disableForeignKeyConstraints();
                    $table->dropColumn('country_id');
                    Schema::enableForeignKeyConstraints();
                }
            });
        }
    }
}
