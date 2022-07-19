<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterInstitutionsGalleryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(Schema::hasTable('institution_gallery')) {
            Schema::table('institution_gallery', function (Blueprint $table) {
                if (!Schema::hasColumn('institution_gallery', 'updated_at')) {
                    $table->dateTime('updated_at')->after('created_at')->nullable();
                }

                if (!Schema::hasColumn('institution_gallery', 'logo_cdn_upload_error')) {
                    $table->text('logo_cdn_upload_error')->after('is_deleted')->nullable();
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
        if(Schema::hasTable('institution_gallery')) {
            Schema::table('institution_gallery', function (Blueprint $table) {
                if (Schema::hasColumn('institution_gallery', 'updated_at')) {
                    $table->dropColumn('updated_at');
                }

                if (Schema::hasColumn('institution_gallery', 'logo_cdn_upload_error')) {
                    $table->dropColumn('logo_cdn_upload_error');
                }
            });
        }
    }
}
