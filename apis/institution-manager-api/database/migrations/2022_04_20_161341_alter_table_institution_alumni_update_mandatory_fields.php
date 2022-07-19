<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AlterTableInstitutionAlumniUpdateMandatoryFields extends Migration
{
    /**
     * Run the migrations.
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('institution_alumni')) {
            Schema::table('institution_alumni', function (Blueprint $table) {
                if (Schema::hasColumn('institution_alumni', 'graduation_year')) {
                    DB::statement('ALTER table institution_alumni modify graduation_year varchar(50) null;');
                }

                if (Schema::hasColumn('institution_alumni', 'current_employer')) {
                    DB::statement('ALTER table institution_alumni modify current_employer varchar(200) null;');
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

    }
}
