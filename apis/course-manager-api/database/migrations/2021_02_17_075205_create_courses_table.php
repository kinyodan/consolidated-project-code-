<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCoursesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('courses')){
            Schema::create('courses', function (Blueprint $table) {
                $table->charset = 'utf8mb4';
                $table->collation = 'utf8mb4_unicode_ci';
                $table->bigIncrements('id');
                $table->string('country_code');
                $table->string('institution_code');
                $table->string('course_code')->unique();
                $table->string('course_name_slug');
                $table->string('course_name')->nullable();
                $table->integer('course_type')->unsigned()->nullable();
                $table->string('institution_course_code')->nullable();
                $table->enum('attendance_type', ['Self Paced','Full Time', 'Evening Classes', 'Part Time'])->nullable();
                $table->text('learning_mode')->nullable();
                $table->string('faculty_code')->nullable();
                $table->text('institution_website_course_url')->nullable();
                $table->text('institution_website_application_form_url')->nullable();
                $table->text('course_requirements')->nullable();
                $table->integer('content_completeness_score')->default(0);
                $table->text('enrollment_details')->nullable();
                $table->tinyInteger('enrollment_in_progress')->default(0);
                $table->enum('standard_fee_billing_type', ['Total Cost','Per Term', 'Per Semester', 'Per Module', 'Per Year', 'Per Unit'])->nullable();
                $table->float('standard_fee_payable', 14)->nullable();
                $table->text('standard_fee_breakdown')->nullable();
                $table->enum('foreign_student_fee_billing_type', ['Total Cost','Per Term', 'Per Semester', 'Per Module', 'Per Year', 'Per Unit'])->nullable();
                $table->float('foreign_student_fee_payable', 14)->nullable();
                $table->text('foreign_student_fee_breakdown')->nullable();
                $table->text('temp_course_image_url')->nullable();
                $table->text('course_small_image')->nullable();
                $table->text('course_image')->nullable();
                $table->text('course_structure_breakdown')->nullable();
                $table->double('course_duration')->nullable();
                $table->enum('course_duration_category', ['Years', 'Months', 'Weeks', 'Days'])->nullable();
                $table->integer('maximum_scholarship_available')->nullable();
                $table->text('accredited_by')->nullable();
                $table->string('accredited_by_acronym')->nullable();
                $table->text('accreditation_organization_url')->nullable();
                $table->string('created_by')->nullable();
                $table->string('updated_by')->nullable();
                $table->string('approved_by')->nullable();
                $table->string('deleted_by')->nullable();
                $table->dateTime('created_at')->nullable();
                $table->dateTime('updated_at')->nullable();
                $table->dateTime('approved_at')->nullable();
                $table->dateTime('deleted_at')->nullable();
                $table->foreign('course_type')
                    ->references('id')
                    ->on('course_types')
                    ->onDelete('cascade');
                $table->unique(['institution_code', 'course_code'], 'unique_course');
                $table->unique(['institution_code', 'course_name_slug'], 'unique_course_name');
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
        if(Schema::hasTable('courses')){
            Schema::disableForeignKeyConstraints();
            Schema::dropIfExists('courses');
            Schema::enableForeignKeyConstraints();
        }
    }
}
