<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AlterCourseCodeNCareerpathwaysToClustersSubjects extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        if(Schema::hasTable('cluster_subjects')) {
            Schema::table('cluster_subjects', function (Blueprint $table) {
                if (!Schema::hasColumn('cluster_subjects', 'course_code')) {
                    $table->Integer('course_code')
                        ->after('is_primary');

                }
                if (!Schema::hasColumn('cluster_subjects', 'career_pathway_id')) {
                    $table->Integer('career_pathway_id')
                        ->after('is_primary');

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

        if(Schema::hasTable('cluster_subjects')) {
            Schema::table('cluster_subjects', function (Blueprint $table) {
                if (Schema::hasColumn('cluster_subjects', 'course_code')) {
                    Schema::disableForeignKeyConstraints();
                    $table->dropColumn('course_code');
                    Schema::enableForeignKeyConstraints();
                }
                if (Schema::hasColumn('cluster_subjects', 'career_pathway_id')) {
                    Schema::disableForeignKeyConstraints();
                    $table->dropColumn('career_pathway_id');
                    Schema::enableForeignKeyConstraints();
                }
            });
        }
    }
}
