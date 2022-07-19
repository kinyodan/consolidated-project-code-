<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableCoursesAddOverviewAndSeoColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(Schema::hasTable('courses')) {
            Schema::table('courses', function (Blueprint $table) {
                if (!Schema::hasColumn('courses', 'course_overview')) {
                    $table->longText('course_overview')->after('course_name')->nullable();
                }

                if (!Schema::hasColumn('courses', 'description')) {
                    $table->longText('description')->after('course_overview')->nullable();
                }

                if (!Schema::hasColumn('courses', 'discipline_code')) {
                    $table->string('discipline_code')->after('course_overview')->nullable();
                }

                if (!Schema::hasColumn('courses', 'meta_keywords')) {
                    $table->text('meta_keywords')->after('accreditation_organization_url')->nullable();
                }

                if (!Schema::hasColumn('courses', 'meta_description')) {
                    $table->text('meta_description')->after('meta_keywords')->nullable();
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
        if(Schema::hasTable('courses')) {
            Schema::table('courses', function (Blueprint $table) {
                if (Schema::hasColumn('courses', 'description')) {
                    $table->dropColumn('description');
                }

                if (Schema::hasColumn('courses', 'discipline_code')) {
                    $table->dropColumn('discipline_code');
                }

                if (Schema::hasColumn('courses', 'course_overview')) {
                    $table->dropColumn('course_overview');
                }

                if (Schema::hasColumn('courses', 'meta_keywords')) {
                    $table->dropColumn('meta_keywords');
                }

                if (Schema::hasColumn('courses', 'meta_description')) {
                    $table->dropColumn('meta_description');
                }
            });
        }
    }
}
