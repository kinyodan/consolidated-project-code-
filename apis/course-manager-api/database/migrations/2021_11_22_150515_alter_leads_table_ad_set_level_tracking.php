<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterLeadsTableAdSetLevelTracking extends Migration
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
                if (!Schema::hasColumn('leads', 'ad_id')) {
                    $table->string('ad_id')->nullable()->after('utm_term');
                }

                if (!Schema::hasColumn('leads', 'ad_set_id')) {
                    $table->string('ad_set_id')->nullable()->after('ad_id');
                }

                if (!Schema::hasColumn('leads', 'campaign_id')) {
                    $table->string('campaign_id')->nullable()->after('ad_set_id');
                }

                if (!Schema::hasColumn('leads', 'ad_name')) {
                    $table->string('ad_name')->nullable()->after('campaign_id');
                }

                if (!Schema::hasColumn('leads', 'ad_set_name')) {
                    $table->string('ad_set_name')->nullable()->after('ad_name');
                }

                if (!Schema::hasColumn('leads', 'ad_placement_position')) {
                    $table->string('ad_placement_position')->nullable()->after('ad_set_name');
                }

                if (!Schema::hasColumn('leads', 'site_source_name')) {
                    $table->string('site_source_name')->nullable()->after('ad_placement_position');
                }

                if (!Schema::hasColumn('leads', 'utm_content')) {
                    $table->text('utm_content')->nullable()->after('site_source_name');
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
                if (Schema::hasColumn('leads', 'ad_id')) {
                    $table->dropColumn('ad_id');
                }

                if (Schema::hasColumn('leads', 'ad_set_id')) {
                    $table->dropColumn('ad_set_id');
                }

                if (Schema::hasColumn('leads', 'campaign_id')) {
                    $table->dropColumn('campaign_id');
                }

                if (Schema::hasColumn('leads', 'ad_name')) {
                    $table->dropColumn('ad_name');
                }

                if (Schema::hasColumn('leads', 'ad_set_name')) {
                    $table->dropColumn('ad_set_name');
                }

                if (Schema::hasColumn('leads', 'ad_placement_position')) {
                    $table->dropColumn('ad_placement_position');
                }

                if (Schema::hasColumn('leads', 'site_source_name')) {
                    $table->dropColumn('site_source_name');
                }

                if (Schema::hasColumn('leads', 'utm_content')) {
                    $table->dropColumn('utm_content');
                }
            });
        }
    }
}
