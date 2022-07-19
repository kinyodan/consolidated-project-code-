<?php

use App\Http\Controllers\Helpers\DBSchemaJHelper;
use App\Models\StudentClass;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AlterTableClassesAddSchoolID extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('classes')) {
            Schema::table('classes', function (Blueprint $table) {
                if (!Schema::hasColumn('classes', 'school_id')) {
                    $table->unsignedBigInteger('school_id')->after('class_name')->default(1);
                    $table->foreign('school_id', 'FK_classes_school_id')
                        ->references('id')
                        ->on((new StudentClass())->getTable())
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
        if (Schema::hasTable('classes')) {
            Schema::table('classes', function (Blueprint $table) {
                if (Schema::hasColumn('classes', 'school_id')
                    && DBSchemaJHelper::tableHasIndex('classes', 'FK_classes_school_id')
                    && env('DB_CONNECTION') !== 'sqlite'
                ) {
                    Schema::disableForeignKeyConstraints();
                    DBSchemaJHelper::runRawQuery("alter table classes drop foreign key FK_classes_school_id;");
                    Schema::enableForeignKeyConstraints();
                }
            });
        }
    }
}
