<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterLeadsTableAddLeadReportingColumns extends Migration
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
                if (!Schema::hasColumn('leads', 'created_by')) {
                    $table->string('created_by')->nullable()->after('lms_provider_error_message');
                }

                if (!Schema::hasColumn('leads', 'updated_by')) {
                    $table->string('updated_by')->nullable()->after('created_by');
                }

                if (!Schema::hasColumn('leads', 'first_updated_on')) {
                    $table->string('first_updated_on')->nullable()->after('created_at');
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
                if (Schema::hasColumn('leads', 'created_by')) {
                    $table->dropColumn('created_by');
                }

                if (Schema::hasColumn('leads', 'updated_by')) {
                    $table->dropColumn('updated_by');
                }

                if (Schema::hasColumn('leads', 'first_updated_on')) {
                    $table->dropColumn('first_updated_on');
                }
            });
        }
    }
}
