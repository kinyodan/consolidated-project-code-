<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterLeadsTableAddSearchToConversionTrackingFields extends Migration
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
                if (!Schema::hasColumn('leads', 'form_type')) {
                    $table->string('form_type')->nullable()->after('utm_campaign');
                }

                if (!Schema::hasColumn('leads', 'page_section')) {
                    $table->text('page_section')->nullable()->after('form_type');
                }

                if (!Schema::hasColumn('leads', 'marketplace_search_query_id')) {
                    $table->text('marketplace_search_query_id')->nullable()->after('page_section');
                }

                if (!Schema::hasColumn('leads', 'marketplace_search_term')) {
                    $table->text('marketplace_search_term')->nullable()->after('page_section');
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
                if (Schema::hasColumn('leads', 'form_type')) {
                    $table->dropColumn('form_type');
                }

                if (Schema::hasColumn('leads', 'page_section')) {
                    $table->dropColumn('page_section');
                }

                if (Schema::hasColumn('leads', 'marketplace_search_query_id')) {
                    $table->dropColumn('marketplace_search_query_id');
                }

                if (Schema::hasColumn('leads', 'marketplace_search_term')) {
                    $table->dropColumn('marketplace_search_term');
                }
            });
        }
    }
}
