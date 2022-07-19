<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterLeadsTableAddStudentCountryLocationColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('leads')) {
            Schema::table('leads', function (Blueprint $table) {
                if (!Schema::hasColumn('leads', 'student_country_location')) {
                    $table->string('student_country_location')
                        ->after('country')
                        ->nullable();
                }

                if (!Schema::hasColumn('leads', 'study_destination')) {
                    $table->string('study_destination')
                        ->after('student_country_location')
                        ->nullable();
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
        if (Schema::hasTable('leads')) {
            Schema::table('leads', function (Blueprint $table) {
                if (Schema::hasColumn('leads', 'student_country_location')) {
                    $table->dropColumn('student_country_location');
                }

                if (Schema::hasColumn('leads', 'study_destination')) {
                    $table->dropColumn('study_destination');
                }
            });
        }
    }
}
