<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AlterLeadsChangeStudentAcademicLevelColumnDefinition extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(Schema::hasTable('leads')) {
            Schema::table('leads', function (Blueprint $table) {
                if (Schema::hasColumn('leads', 'student_academic_level')) {
                    DB::statement("ALTER TABLE `leads` MODIFY `student_academic_level` varchar(255) null");
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
        if(Schema::hasTable('leads')) {
            Schema::table('leads', function (Blueprint $table) {
                if (Schema::hasColumn('leads', 'student_academic_level')) {
                    DB::statement('ALTER TABLE leads MODIFY `student_academic_level` int(10) null');
                }
            });
        }
    }
}
