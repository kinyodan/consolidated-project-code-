<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableInstitutionsAddTempFilePathColumn extends Migration
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
                if(!Schema::hasColumn('institutions', 'temp_logo_path')){
                    $table->text('temp_logo_path')->nullable()->after('website_url');
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
                if(Schema::hasColumn('institutions', 'temp_logo_path')){
                    $table->dropColumn('temp_logo_path');
                }
            });
        }
    }
}
