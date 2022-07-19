<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterLeadsTableAddYearOfBirth extends Migration
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
                if(!Schema::hasColumn('leads', 'year_of_birth')){
                    $table->string('year_of_birth')->nullable()->after('last_name');
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
                if(Schema::hasColumn('leads', 'year_of_birth')){
                    $table->dropColumn('year_of_birth');
                }
            });
        }
    }
}
