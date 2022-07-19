<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableInstitutionAddColumn extends Migration
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
                if(!Schema::hasColumn('institutions', 'accreditation_body_url')){
                    $table->text('accreditation_body_url')->nullable()->after('accredited_by');
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
                if(Schema::hasColumn('institutions', 'accreditation_body_url')){
                    $table->dropColumn('accreditation_body_url');
                }
            });
        }
    }
}
