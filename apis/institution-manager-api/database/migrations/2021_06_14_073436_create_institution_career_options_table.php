<?php
use App\Models\Institution;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInstitutionCareerOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('institution_career_options')) {
            Schema::create('institution_career_options', function (Blueprint $table) {
                $table->charset = 'utf8mb4';
                $table->collation = 'utf8mb4_unicode_ci';
                $table->bigIncrements('id');
                $table->string('institution_code');
                $table->text('career_fact');
                $table->text('career_fact_description');
                $table->integer('display_order')->nullable();
                $table->tinyInteger('is_active')->default(1);
                $table->tinyInteger('is_deleted')->default(0);
                $table->string('created_by')->nullable();
                $table->string('updated_by')->nullable();
                $table->string('deleted_by')->nullable();
                $table->dateTimeTz('created_at')->nullable();
                $table->dateTimeTz('updated_at')->nullable();
                $table->dateTimeTz('deleted_at')->nullable();
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
        if(Schema::hasTable('institution_career_options')){
            Schema::disableForeignKeyConstraints();
            Schema::dropIfExists('institution_career_options');
            Schema::enableForeignKeyConstraints();
        }
    }
}
