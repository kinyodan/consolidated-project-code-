<?php
use App\Models\Institution;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInstitutionHighlightsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('institution_highlights')) {
            Schema::create('institution_highlights', function (Blueprint $table) {
                $table->charset = 'utf8mb4';
                $table->collation = 'utf8mb4_unicode_ci';
                $table->bigIncrements('id');
                $table->string('institution_code');
                $table->string('key_highlight');
                $table->text('key_highlight_description')->nullable();
                $table->integer('display_order')->nullable();
                $table->tinyInteger('is_active')->default(1);
                $table->tinyInteger('is_deleted')->default(0);
                $table->string('created_by')->nullable();
                $table->string('updated_by')->nullable();
                $table->string('deleted_by')->nullable();
                $table->dateTimeTz('created_at')->nullable();
                $table->dateTimeTz('updated_at')->nullable();
                $table->dateTimeTz('deleted_at')->nullable();
                $table->unique(['institution_code', 'key_highlight'], 'unique_institution_highlight');
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
        if(Schema::hasTable('institution_highlights')){
            Schema::disableForeignKeyConstraints();
            Schema::dropIfExists('institution_highlights');
            Schema::enableForeignKeyConstraints();
        }
    }
}
