<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterLeadsTableAddParentNameColumn extends Migration
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
                if (!Schema::hasColumn('leads', 'parent_full_names')) {
                    $table->string('parent_full_names')
                        ->after('year_of_birth')
                        ->nullable();
                }

                if (!Schema::hasColumn('leads', 'parent_mobile_number')) {
                    $table->string('parent_mobile_number')
                        ->after('parent_full_names')
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
                if (Schema::hasColumn('leads', 'parent_full_names')) {
                    $table->dropColumn('parent_full_names');
                }

                if (Schema::hasColumn('leads', 'parent_mobile_number')) {
                    $table->dropColumn('parent_mobile_number');
                }
            });
        }
    }
}
