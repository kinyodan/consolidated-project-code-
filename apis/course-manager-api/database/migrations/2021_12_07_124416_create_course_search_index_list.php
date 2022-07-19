<?php
use App\Models\AcademicDiscipline;
use App\Models\Course;
use App\Models\CourseType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCourseSearchIndexList extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('course_search_index_list')) {
            Schema::create('course_search_index_list', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('country_code');
                $table->string('institution_code');
                $table->string('course_code');
                $table->string('course_name_slug');
                $table->string('course_name');
                $table->decimal('course_rating');
                $table->integer('total_institution_enrollments')->default(0);
                $table->integer('total_leads_submitted_to_crm')->default(0);
                $table->tinyInteger('is_featured')->default(0);
                $table->tinyInteger('is_deleted')->default(0);
                $table->tinyInteger('is_active')->default(1);
                $table->tinyInteger('is_published')->default(1);
                $table->tinyInteger('should_unpublish')->default(0);
                $table->tinyInteger('is_picked_for_unpublishing')->default(0);
                $table->text('description')->nullable();
                $table->text('course_overview')->nullable();
                $table->unsignedBigInteger('discipline_code')->nullable();
                $table->unsignedInteger('course_type')->nullable();
                $table->string('graduate_level')->nullable();
                $table->string('attendance_type')->nullable();
                $table->double('internal_course_ranking')->default(0.10);
                $table->string('institution_course_code')->nullable();
                $table->string('faculty_code')->nullable();
                $table->string('learning_mode')->nullable();
                $table->text('institution_website_course_url')->nullable();
                $table->text('institution_website_application_form_url')->nullable();
                $table->text('course_requirements')->nullable();
                $table->integer('content_completeness_score')->default(0);
                $table->integer('enrollment_details')->nullable();
                $table->tinyInteger('enrollment_in_progress')->default(0);
                $table->string('standard_fee_billing_type')->default(0);
                $table->double('standard_fee_payable', 14, 2);
                $table->double('standard_fee_payable_usd', 14, 2);
                $table->tinyInteger('ignore_first_year_fees_compute_based_on_total')->default(0);
                $table->double('standard_first_year_fee_payable_usd', 14, 2);
                $table->tinyInteger('standard_first_year_fee_payable_usd_is_manual')->default(0);
                $table->double('foreign_student_fee_payable_usd', 14, 2);
                $table->double('foreign_student_first_year_fee_payable_usd', 14, 2);
                $table->tinyInteger('foreign_student_first_year_fee_payable_usd_is_manual')->default(0);
                $table->text('standard_fee_breakdown')->nullable();
                $table->string('foreign_student_fee_billing_type')->nullable();
                $table->double('foreign_student_fee_payable', 14, 2)->nullable();
                $table->text('foreign_student_fee_breakdown')->nullable();
                $table->text('temp_course_image_url')->nullable();
                $table->text('course_small_image')->nullable();
                $table->text('course_image')->nullable();
                $table->text('course_structure_breakdown')->nullable();
                $table->double('course_duration')->nullable();
                $table->string('course_duration_category')->nullable();
                $table->integer('maximum_scholarship_available')->nullable();
                $table->text('accredited_by')->nullable();
                $table->string('accredited_by_acronym')->nullable();
                $table->text('accreditation_organization_url')->nullable();
                $table->text('meta_keywords')->nullable();
                $table->text('meta_description')->nullable();
                $table->text('linked_blog_articles')->nullable();
                $table->string('created_by')->nullable();
                $table->string('updated_by')->nullable();
                $table->string('approved_by')->nullable();
                $table->string('deleted_by')->nullable();
                $table->dateTime('approved_at')->nullable();
                $table->dateTime('deleted_at')->nullable();
                $table->string('currency')->nullable();
                $table->text('fees_structure_url')->nullable();
                $table->float('popularity', 10, 10)->nullable();
                $table->string('indexing_object_id')->unique('indexing_object_id');
                $table->tinyInteger('requires_indexing')->default(0);
                $table->tinyInteger('has_updates')->default(1);
                $table->tinyInteger('is_picked_for_indexing')->default(0);
                $table->dateTime('time_picked_for_indexing')->nullable();
                $table->decimal('time_taken_to_index', 8, 2)->nullable();
                $table->text('indexing_error')->nullable();
                $table->dateTime('created_at')->nullable();
                $table->dateTime('updated_at')->nullable();
                $table->unique(['course_code', 'discipline_code'], 'unique_course_discipline');
                $table->unique(['institution_code', 'course_name_slug', 'graduate_level', 'discipline_code'], 'unique_course_discipline_graduate');
                $table->foreign('course_type')
                    ->references('id')
                    ->on((new CourseType())->getTable())
                    ->onDelete('cascade');
                $table->foreign('course_code')
                    ->references('course_code')
                    ->on((new Course())->getTable())
                    ->onDelete('cascade');
                $table->foreign('discipline_code')
                    ->references('id')
                    ->on((new AcademicDiscipline())->getTable())
                    ->onDelete('cascade');
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
        if(Schema::hasTable('course_search_index_list')) {
            Schema::disableForeignKeyConstraints();
            Schema::dropIfExists('course_search_index_list');
            Schema::enableForeignKeyConstraints();
        }
    }
}
