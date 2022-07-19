<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterSchoolsTableAddInverseLogoColumn extends Migration
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
                if(!Schema::hasColumn('schools','school_inverse_logo_url')) {
                    $table->text('school_inverse_logo_url')->after('school_logo_url')->nullable();
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
                if(Schema::hasColumn('schools','school_inverse_logo_url')) {
                    $table->dropColumn('school_inverse_logo_url');
                }
            });
        }
    }
}
