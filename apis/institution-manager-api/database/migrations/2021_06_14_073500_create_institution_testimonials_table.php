<?php
use App\Models\Institution;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInstitutionTestimonialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('institution_testimonials')) {
            Schema::create('institution_testimonials', function (Blueprint $table) {
                $table->charset = 'utf8mb4';
                $table->collation = 'utf8mb4_unicode_ci';
                $table->bigIncrements('id');
                $table->string('institution_code');
                $table->string('student_name_slug');
                $table->string('student_name');
                $table->text('course_studied')->nullable();
                $table->text('student_image')->nullable();
                $table->text('current_employment_position')->nullable();
                $table->text('current_employer')->nullable();
                $table->string('country_name')->nullable();
                $table->text('testimonial_intro')->nullable();
                $table->text('testimonial_details')->nullable();
                $table->integer('display_order')->nullable();
                $table->tinyInteger('is_active')->default(1);
                $table->tinyInteger('is_deleted')->default(0);
                $table->string('created_by')->nullable();
                $table->string('updated_by')->nullable();
                $table->string('deleted_by')->nullable();
                $table->dateTimeTz('created_at')->nullable();
                $table->dateTimeTz('updated_at')->nullable();
                $table->dateTimeTz('deleted_at')->nullable();
                $table->unique(['institution_code', 'student_name_slug'], 'unique_student_testimonial');
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
        if(Schema::hasTable('institution_testimonials')){
            Schema::disableForeignKeyConstraints();
            Schema::dropIfExists('institution_testimonials');
            Schema::enableForeignKeyConstraints();
        }
    }
}
