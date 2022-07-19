<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterLeadsTableAddGenderAndProgressiveLeadFlag extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(Schema::hasTable('leads')) {
            Schema::table('leads', function (Blueprint $table) {
                if (!Schema::hasColumn('leads', 'student_has_passport')) {
                    $table->string('student_has_passport')
                        ->after('student_academic_level')
                        ->nullable();
                }

                if (!Schema::hasColumn('leads', 'used_progressive_lead_form')) {
                    $table->string('used_progressive_lead_form')
                        ->after('student_has_passport')
                        ->nullable();
                }

                if (!Schema::hasColumn('leads', 'target_budget')) {
                    $table->string('target_budget')
                        ->after('used_progressive_lead_form')
                        ->nullable();
                }

                if (!Schema::hasColumn('leads', 'course_name')) {
                    $table->text('course_name')
                        ->after('target_budget')
                        ->nullable();
                }
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
        if(Schema::hasTable('leads')) {
            Schema::table('leads', function (Blueprint $table) {
                if (Schema::hasColumn('leads', 'student_has_passport')) {
                    $table->dropColumn('student_has_passport');
                }

                if (Schema::hasColumn('leads', 'used_progressive_lead_form')) {
                    $table->dropColumn('used_progressive_lead_form');
                }

                if (Schema::hasColumn('leads', 'target_budget')) {
                    $table->dropColumn('target_budget');
                }

                if (Schema::hasColumn('leads', 'course_name')) {
                    $table->dropColumn('course_name');
                }
            });
        }
    }
}
