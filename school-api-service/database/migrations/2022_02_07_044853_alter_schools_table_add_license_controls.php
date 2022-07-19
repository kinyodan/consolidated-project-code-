<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterSchoolsTableAddLicenseControls extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(Schema::hasTable('schools')) {
            Schema::table('schools', function (Blueprint $table) {
                if(!Schema::hasColumn('schools','allowed_license_count')) {
                    $table->integer('allowed_license_count')->after('is_verified')->default(0);
                }

                if(!Schema::hasColumn('schools','school_has_to_collect_full-payment')) {
                    $table->tinyInteger('school_has_to_collect_full')->after('allowed_license_count')->default(1);
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
        if(Schema::hasTable('schools')) {
            Schema::table('schools', function (Blueprint $table) {
                if(Schema::hasColumn('schools','allowed_license_count')) {
                    $table->dropColumn('allowed_license_count');
                }

                if(Schema::hasColumn('schools','school_has_to_collect_full-payment')) {
                    $table->dropColumn('school_has_to_collect_full');
                }
            });
        }
    }
}
