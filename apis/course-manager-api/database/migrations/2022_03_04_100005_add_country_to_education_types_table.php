<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCountryToEducationTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(Schema::hasTable('education_types')) {
            Schema::table('education_types', function (Blueprint $table) {
                if (!Schema::hasColumn('education_types', 'country_id')) {
                    $table->Integer('country_id')
                        ->after('education_type_name');
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
        if(Schema::hasTable('education_types')) {
            Schema::table('education_types', function (Blueprint $table) {
                if (Schema::hasColumn('education_types', 'country_id')) {
                    Schema::disableForeignKeyConstraints();
                    $table->dropColumn('country_id');
                    Schema::enableForeignKeyConstraints();
                }
            });
        }
    }
}
