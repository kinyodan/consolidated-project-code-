<?php
use App\Models\Institution;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInstitutionAlumniTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('institution_alumni')) {
            Schema::create('institution_alumni', function (Blueprint $table) {
                $table->charset = 'utf8mb4';
                $table->collation = 'utf8mb4_unicode_ci';
                $table->bigIncrements('id');
                $table->string('institution_code');
                $table->string('alumni_name_slug');
                $table->string('alumni_name');
                $table->string('graduation_year');
                $table->text('course_taken');
                $table->text('current_employer');
                $table->text('current_position');
                $table->text('personal_profile_url')->nullable();
                $table->text('alumni_image')->nullable();
                $table->integer('display_order')->nullable();
                $table->tinyInteger('is_active')->default(1);
                $table->tinyInteger('is_deleted')->default(0);
                $table->string('created_by')->nullable();
                $table->string('updated_by')->nullable();
                $table->string('deleted_by')->nullable();
                $table->dateTimeTz('created_at')->nullable();
                $table->dateTimeTz('updated_at')->nullable();
                $table->dateTimeTz('deleted_at')->nullable();
                $table->unique(['institution_code', 'alumni_name_slug'], 'unique_institution_alumni');
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
        if(Schema::hasTable('institution_alumni')){
            Schema::disableForeignKeyConstraints();
            Schema::dropIfExists('institution_alumni');
            Schema::enableForeignKeyConstraints();
        }
    }
}
