<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AlterCurrentPositionToInstitutionAlumni extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('institution_alumni', function (Blueprint $table) {
            if(Schema::hasTable('institution_alumni')) {
                Schema::table('institution_alumni', function (Blueprint $table) {
                    if (Schema::hasColumn('institution_alumni', 'current_position')) {
                        DB::statement('ALTER table institution_alumni modify current_position text  null;');
                    }
                    if (!Schema::hasColumn('institution_alumni', 'email')) {
                        $table->text('email')->after('current_position')->nullable();
                    }
                    if (!Schema::hasColumn('institution_alumni', 'university_name')) {
                        $table->text('university_name')->after('email')->nullable();
                    }
                });
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('institution_alumni')) {
            Schema::table('institution_alumni', function (Blueprint $table) {
                if (Schema::hasColumn('institution_alumni', 'current_position')) {
                    DB::statement('ALTER table institution_alumni modify current_position bigint unsigned not null;');
                }
                if (Schema::hasColumn('institution_alumni', 'university_name')) {
                    $table->dropColumn('university_name');
                }
                if (Schema::hasColumn('institution_alumni', 'email')) {
                    $table->dropColumn('email');
                }
            });
        }
    }
}
