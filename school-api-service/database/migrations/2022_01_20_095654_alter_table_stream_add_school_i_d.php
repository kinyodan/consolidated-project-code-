<?php

use App\Http\Controllers\Helpers\DBSchemaJHelper;
use App\Models\StudentStream;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AlterTableStreamAddSchoolID extends Migration
{
    /**
     * Run the migrations.
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('streams')) {
            Schema::table('streams', function (Blueprint $table) {
                if (!Schema::hasColumn('streams', 'school_id')) {
                    $table->unsignedBigInteger('school_id')->after('stream_name')->default(1);
                    $table->foreign('school_id', 'FK_streams_school_id')
                        ->references('id')
                        ->on((new StudentStream())->getTable())
                        ->onDelete('cascade');
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
        if (Schema::hasTable('streams')) {
            Schema::table('streams', function (Blueprint $table) {
                if (Schema::hasColumn('streams', 'school_id')
                    && DBSchemaJHelper::tableHasIndex('streams', 'FK_streams_school_id')
                    && env('DB_CONNECTION') !== 'sqlite'
                ) {
                    Schema::disableForeignKeyConstraints();
                    DBSchemaJHelper::runRawQuery("alter table streams drop foreign key FK_streams_school_id;");
                    Schema::enableForeignKeyConstraints();
                }
            });
        }
    }
}
