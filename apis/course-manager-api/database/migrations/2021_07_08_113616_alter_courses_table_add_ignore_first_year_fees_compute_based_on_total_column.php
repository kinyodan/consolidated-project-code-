<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterCoursesTableAddIgnoreFirstYearFeesComputeBasedOnTotalColumn extends Migration
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
                if (!Schema::hasColumn('courses', 'ignore_first_year_fees_compute_based_on_total')) {
                    $table->tinyInteger('ignore_first_year_fees_compute_based_on_total')->default(0)->after('standard_fee_payable_usd');
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
                if (Schema::hasColumn('courses', 'ignore_first_year_fees_compute_based_on_total')) {
                    $table->dropColumn('ignore_first_year_fees_compute_based_on_total');
                }
            });
        }
    }
}
