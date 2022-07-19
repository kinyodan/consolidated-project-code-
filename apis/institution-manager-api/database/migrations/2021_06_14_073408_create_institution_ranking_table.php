<?php
use App\Models\Institution;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInstitutionRankingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('institution_ranking')) {
            Schema::create('institution_ranking', function (Blueprint $table) {
                $table->charset = 'utf8mb4';
                $table->collation = 'utf8mb4_unicode_ci';
                $table->bigIncrements('id');
                $table->string('institution_code');
                $table->string('rank');
                $table->text('rank_description')->nullable();
                $table->string('ranked_by_acronym')->nullable();
                $table->text('ranked_by')->nullable();
                $table->text('ranked_by_image')->nullable();
                $table->text('ranked_by_website')->nullable();
                $table->integer('display_order')->nullable();
                $table->tinyInteger('is_active')->default(1);
                $table->tinyInteger('is_deleted')->default(0);
                $table->string('created_by')->nullable();
                $table->string('updated_by')->nullable();
                $table->string('deleted_by')->nullable();
                $table->dateTimeTz('created_at')->nullable();
                $table->dateTimeTz('updated_at')->nullable();
                $table->dateTimeTz('deleted_at')->nullable();
                $table->unique(['institution_code', 'rank'], 'unique_institution_rank');
                $table->foreign('institution_code')
                    ->references('institution_code')
                    ->on((new Institution())->getTable());
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
        if(Schema::hasTable('institution_ranking')){
            Schema::disableForeignKeyConstraints();
            Schema::dropIfExists('institution_ranking');
            Schema::enableForeignKeyConstraints();
        }
    }
}
