<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterLeadsTableAddCampaignTrackingColumns extends Migration
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
                if(!Schema::hasColumn($table->getTable(), 'utm_source')){
                    $table->text('utm_source')->nullable()->after('referrer_url');
                }

                if(!Schema::hasColumn($table->getTable(), 'utm_medium')){
                    $table->text('utm_medium')->nullable()->after('utm_source');
                }

                if(!Schema::hasColumn($table->getTable(), 'utm_campaign')){
                    $table->text('utm_campaign')->nullable()->after('utm_medium');
                }

                if(!Schema::hasColumn($table->getTable(), 'asset_id')){
                    $table->text('asset_id')->nullable()->after('utm_campaign');
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
                if(Schema::hasColumn($table->getTable(), 'utm_source')){
                    $table->dropColumn('utm_source');
                }

                if(Schema::hasColumn($table->getTable(), 'utm_medium')){
                    $table->dropColumn('utm_medium');
                }

                if(Schema::hasColumn($table->getTable(), 'utm_campaign')){
                    $table->dropColumn('utm_campaign');
                }

                if(Schema::hasColumn($table->getTable(), 'asset_id')){
                    $table->dropColumn('asset_id');
                }
            });
        }
    }
}
