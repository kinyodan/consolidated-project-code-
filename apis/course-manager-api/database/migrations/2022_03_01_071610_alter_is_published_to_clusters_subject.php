<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AlterIsPublishedToClustersSubject extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(Schema::hasTable('cluster_subjects')) {
            Schema::table('cluster_subjects', function (Blueprint $table) {
                if (!Schema::hasColumn('cluster_subjects', 'is_published')) {
                    $table->tinyInteger('is_published')
                        ->after('is_primary')
                        ->default(1);
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
        if(Schema::hasTable('cluster_subjects')) {
            Schema::table('cluster_subjects', function (Blueprint $table) {
                if (Schema::hasColumn('cluster_subjects', 'is_published')) {
                    Schema::disableForeignKeyConstraints();
                    $table->dropColumn('is_published');
                    Schema::enableForeignKeyConstraints();
                }
            });
        }
    }
}
