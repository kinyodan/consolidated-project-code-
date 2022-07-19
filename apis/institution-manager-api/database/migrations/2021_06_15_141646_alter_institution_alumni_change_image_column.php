<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AlterInstitutionAlumniChangeImageColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(Schema::hasTable('institution_alumni')) {
            Schema::table('institution_alumni', function (Blueprint $table) {
                if (Schema::hasColumn('institution_alumni', 'alumni_image')) {
                    DB::statement('ALTER TABLE institution_alumni CHANGE COLUMN alumni_image temp_image_path varchar(255)');
                }
            });

            Schema::table('institution_alumni', function (Blueprint $table) {
                if (!Schema::hasColumn('institution_alumni', 'big_alumnus_image_path')) {
                    $table->text('big_alumnus_image_path')->nullable()->after('temp_image_path');
                }

                if (!Schema::hasColumn('institution_alumni', 'small_alumnus_image_path')) {
                    $table->text('small_alumnus_image_path')->nullable()->after('big_alumnus_image_path');
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
        if(Schema::hasTable('institution_alumni')) {
            Schema::table('institution_alumni', function (Blueprint $table) {
                if (Schema::hasColumn('institution_alumni', 'temp_image_path')) {
                    DB::statement('ALTER TABLE institution_alumni CHANGE COLUMN  temp_image_path alumni_image  varchar(255)');

                }

                if (Schema::hasColumn('institution_alumni', 'big_alumnus_image_path')) {
                    $table->dropColumn('big_alumnus_image_path');
                }

                if (Schema::hasColumn('institution_alumni', 'small_alumnus_image_path')) {
                    $table->dropColumn('small_alumnus_image_path');
                }
            });
        }
    }
}
