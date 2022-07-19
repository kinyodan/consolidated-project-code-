<?php
use App\Models\Institution;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInstitutionAccreditationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('institution_accreditations')){
            Schema::create('institution_accreditations', function (Blueprint $table) {
                $table->charset = 'utf8mb4';
                $table->collation = 'utf8mb4_unicode_ci';
                $table->bigIncrements('id');
                $table->string('institution_code');
                $table->string('organization_name_slug')->unique('organization_name_slug');
                $table->string('organization_name')->nullable();
                $table->string('organization_acronym')->nullable();
                $table->text('temp_image_path')->nullable();
                $table->text('big_organization_image')->nullable();
                $table->text('small_organization_image')->nullable();
                $table->text('accreditation_description')->nullable();
                $table->integer('display_order')->nullable();
                $table->tinyInteger('is_active')->default(1);
                $table->tinyInteger('is_deleted')->default(0);
                $table->string('created_by')->nullable();
                $table->string('updated_by')->nullable();
                $table->string('deleted_by')->nullable();
                $table->dateTimeTz('created_at')->nullable();
                $table->dateTimeTz('updated_at')->nullable();
                $table->dateTimeTz('deleted_at')->nullable();
                $table->unique(['institution_code', 'organization_name_slug'], 'unique_institution_accreditation');
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
        if(Schema::hasTable('institution_accreditations')){
            Schema::disableForeignKeyConstraints();
            Schema::dropIfExists('institution_accreditations');
            Schema::enableForeignKeyConstraints();
        }
    }
}
