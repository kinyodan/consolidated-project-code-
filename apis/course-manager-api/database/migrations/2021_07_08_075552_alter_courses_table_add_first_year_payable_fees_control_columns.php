<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterCoursesTableAddFirstYearPayableFeesControlColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(Schema::hasTable('courses')) {
            Schema::table('courses', function (Blueprint $table) {
                if (!Schema::hasColumn('courses', 'standard_first_year_fee_payable_usd_is_manual')) {
                    $table->tinyInteger('standard_first_year_fee_payable_usd_is_manual')->default(0)->after('standard_first_year_fee_payable_usd');
                }

                if (!Schema::hasColumn('courses', 'foreign_student_first_year_fee_payable_usd_is_manual')) {
                    $table->tinyInteger('foreign_student_first_year_fee_payable_usd_is_manual')->default(0)->after('foreign_student_first_year_fee_payable_usd');
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
        if(Schema::hasTable('courses')) {
            Schema::table('courses', function (Blueprint $table) {
                Schema::table('courses', function (Blueprint $table) {
                    if (Schema::hasColumn('courses', 'standard_first_year_fee_payable_usd_is_manual')) {
                        $table->dropColumn('standard_first_year_fee_payable_usd_is_manual');
                    }

                    if (Schema::hasColumn('courses', 'foreign_student_first_year_fee_payable_usd_is_manual')) {
                        $table->dropColumn('foreign_student_first_year_fee_payable_usd_is_manual');
                    }
                });
            });
        }
    }
}
