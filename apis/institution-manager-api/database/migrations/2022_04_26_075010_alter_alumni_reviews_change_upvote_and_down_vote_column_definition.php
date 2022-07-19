<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AlterAlumniReviewsChangeUpvoteAndDownVoteColumnDefinition extends Migration
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
                if (Schema::hasColumn('alumni_reviews', 'up_vote')) {
                    DB::statement("ALTER table ".$table->getTable(). " modify up_vote int(10) null default 0;");
                }

                if (Schema::hasColumn('alumni_reviews', 'down_vote')) {
                    DB::statement("ALTER table ".$table->getTable(). " modify down_vote int(10) null default 0;");
                }

                if (Schema::hasColumn('alumni_reviews', 'flagged')) {
                    DB::statement("ALTER table ".$table->getTable(). " modify flagged int(10) null default 0;");
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
                if (Schema::hasColumn('alumni_reviews', 'up_vote')) {
                    DB::statement("ALTER table ".$table->getTable(). " modify up_vote varchar(50) null;");
                }

                if (Schema::hasColumn('alumni_reviews', 'down_vote')) {
                    DB::statement("ALTER table ".$table->getTable(). " modify down_vote varchar(50) null;");
                }

                if (Schema::hasColumn('alumni_reviews', 'flagged')) {
                    DB::statement("ALTER table ".$table->getTable(). " modify flagged int(10) int(10) null;");
                }
            });
        }
    }
}
