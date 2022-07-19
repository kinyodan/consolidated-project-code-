<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterCoursesTableAddForeignStudentFeePayableUSDColumn extends Migration
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
                if (!Schema::hasColumn('courses', 'foreign_student_fee_payable_usd')) {
                    $table->float('foreign_student_fee_payable_usd', 14, 2)->nullable()->after('standard_fee_payable_usd');
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
                if (Schema::hasColumn('courses', 'foreign_student_fee_payable_usd')) {
                    $table->dropColumn('foreign_student_fee_payable_usd');
                }
            });
        }
    }
}
