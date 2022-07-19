<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterStudentsTableAddImportBatchNumber extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(Schema::hasTable('students')) {
            Schema::table('students', function (Blueprint $table) {
                if (!Schema::hasColumn('students', 'import_batch_no')) {
                    $table->string('import_batch_no')->nullable()->after('has_applied_for_course');
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
        if(Schema::hasTable('students') && env('DB_CONNECTION') !== 'sqlite') {
            Schema::table('students', function (Blueprint $table) {
                if (Schema::hasColumn('students', 'import_batch_no')) {
                    $table->dropColumn('import_batch_no');
                }
            });
        }
    }
}
