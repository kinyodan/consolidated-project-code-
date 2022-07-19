<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableCountryChangeColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(Schema::hasTable('countries')) {
            Schema::table('countries', function (Blueprint $table) {
                if(Schema::hasColumn('countries', 'continent_id')){
                    $table->renameColumn('continent_id', 'continent');
                }

                if(Schema::hasColumn('countries', 'geo_region_id')){
                    $table->renameColumn('geo_region_id', 'geographical_region');
                }
            });

            Schema::table('countries', function (Blueprint $table) {
                if(Schema::hasColumn('countries', 'continent')){
                    $table->string('continent', 250)->change();
                }

                if(Schema::hasColumn('countries', 'geographical_region')){
                    $table->string('geographical_region', 250)->change();
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
        if(Schema::hasTable('countries')) {
            Schema::table('countries', function (Blueprint $table) {
                if(Schema::hasColumn('countries', 'continent_id')){
                    $table->renameColumn('continent', 'continent_id');
                }

                if(Schema::hasColumn('countries', 'geo_region_id')){
                    $table->renameColumn('geographical_region', 'geo_region_id');
                }
            });

            Schema::table('countries', function (Blueprint $table) {
                if(Schema::hasColumn('countries', 'continent_id')){
                    $table->integer('continent_id')->change();
                }

                if(Schema::hasColumn('countries', 'geo_region_id')){
                    $table->integer('geo_region_id')->change();
                }
            });
        }
    }
}
