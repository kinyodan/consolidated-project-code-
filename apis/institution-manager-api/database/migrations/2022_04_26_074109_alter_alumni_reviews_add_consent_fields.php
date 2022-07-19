<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterAlumniReviewsAddConsentFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        if (Schema::hasTable('alumni_reviews')) {
            Schema::table('alumni_reviews', function (Blueprint $table) {
                if (!Schema::hasColumn('alumni_reviews', 'customer_consented_to_review_publish')) {
                    $table->tinyInteger('customer_consented_to_review_publish')->default(1)->after('reviews');
                }

                if (!Schema::hasColumn('alumni_reviews', 'customer_consented_to_use_linkedin_photo')) {
                    $table->tinyInteger('customer_consented_to_use_linkedin_photo')->default(1)->after('customer_consented_to_review_publish');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        if (Schema::hasTable('alumni_reviews')) {
            Schema::table('alumni_reviews', function (Blueprint $table) {
                if (Schema::hasColumn('alumni_reviews', 'customer_consented_to_review_publish')) {
                    $table->dropColumn('customer_consented_to_review_publish');
                }

                if (Schema::hasColumn('alumni_reviews', 'customer_consented_to_use_linkedin_photo')) {
                    $table->dropColumn('customer_consented_to_use_linkedin_photo');
                }
            });
        }
    }
}
