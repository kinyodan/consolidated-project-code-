<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AlterInstitutionAlumniChangeQuestionStepToVarchar extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('institution_alumni')) {
            Schema::table('institution_alumni', function (Blueprint $table) {
                if (Schema::hasColumn($table->getTable(), 'question_step')) {
                    DB::statement("ALTER table ".$table->getTable()." modify question_step varchar(50) null;");
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
        if (Schema::hasTable('institution_alumni')) {
            Schema::table('institution_alumni', function (Blueprint $table) {
                if (Schema::hasColumn($table->getTable(), 'question_step')) {
                    DB::statement("ALTER table ".$table->getTable(). "modify question_step tinyint(1) null;");
                }
            });
        }
    }
}
