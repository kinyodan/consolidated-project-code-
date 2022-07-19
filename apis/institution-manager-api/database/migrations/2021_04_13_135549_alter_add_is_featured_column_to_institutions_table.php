<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterAddIsFeaturedColumnToInstitutionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(Schema::hasTable('institutions')) {
            Schema::table('institutions', function (Blueprint $table) {
                if (!Schema::hasColumn('institutions', 'is_featured')) {
                    $table->tinyInteger('is_featured')->default(0)->after('institution_name')->nullable();
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
        if(Schema::hasTable('institutions')) {
            Schema::table('institutions', function (Blueprint $table) {
                if (Schema::hasColumn('institutions', 'is_featured')) {
                    $table->dropColumn('is_featured');
                }
            });
        }
    }
}
