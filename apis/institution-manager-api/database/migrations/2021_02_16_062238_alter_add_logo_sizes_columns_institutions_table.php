<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterAddLogoSizesColumnsInstitutionsTable extends Migration
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
                if(!Schema::hasColumn('institutions', 'logo_url_small')){
                    $table->text('logo_url_small')->nullable()->after('logo_url');
                }

                if(!Schema::hasColumn('institutions', 'logo_cdn_upload_error')){
                    $table->text('logo_cdn_upload_error')->nullable()->after('logo_url_small');
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
                if(Schema::hasColumn('institutions', 'logo_url_small')){
                    $table->dropColumn('logo_url_small');
                }

                if(Schema::hasColumn('institutions', 'logo_cdn_upload_error')){
                    $table->dropColumn('logo_cdn_upload_error');
                }
            });
        }
    }
}
