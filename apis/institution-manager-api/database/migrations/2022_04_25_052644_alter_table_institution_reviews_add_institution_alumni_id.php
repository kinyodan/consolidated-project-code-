<?php
use App\Helpers\DBSchemaJHelper;
use App\Models\InstitutionAlumnus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AlterTableInstitutionReviewsAddInstitutionAlumniId extends Migration
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
                if (Schema::hasColumn('alumni_reviews', 'institution_alumni_id')) {
                    DB::statement("ALTER table ".$table->getTable(). " modify institution_alumni_id BIGINT unsigned;");
                    $table
                        ->foreign('institution_alumni_id', 'fk_institution_alumni_review_link')
                        ->references('id')
                        ->on((new InstitutionAlumnus())->getTable());
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
                if (DBSchemaJHelper::tableHasIndex($table->getTable(), 'fk_institution_alumni_review_link')) {
                    Schema::disableForeignKeyConstraints();
                    $table->dropForeign('fk_institution_alumni_review_link');
                    Schema::enableForeignKeyConstraints();
                }
            });
        }
    }
}
