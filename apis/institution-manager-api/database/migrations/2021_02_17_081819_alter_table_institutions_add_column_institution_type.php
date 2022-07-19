<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableInstitutionsAddColumnInstitutionType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(Schema::hasTable('institutions')) {
            Schema::table('institutions', function (Blueprint $table) {
                if(!Schema::hasColumn('institutions', 'institution_type')){
                    $table->integer('institution_type')->nullable()->after('institution_code');
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
        if(Schema::hasTable('institutions')) {
            Schema::table('institutions', function (Blueprint $table) {
                if(Schema::hasColumn('institutions', 'institution_type')){
                    $table->dropColumn('institution_type');
                }
            });
        }
    }
}
