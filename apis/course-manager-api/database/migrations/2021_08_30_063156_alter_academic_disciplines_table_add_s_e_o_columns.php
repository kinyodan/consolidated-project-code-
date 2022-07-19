<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterAcademicDisciplinesTableAddSEOColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(Schema::hasTable('academic_disciplines')) {
            Schema::table('academic_disciplines', function (Blueprint $table) {
                if (!Schema::hasColumn('academic_disciplines', 'seo_page_title')) {
                    $table->text('seo_page_title')->nullable()->after('discipline_large_icon');
                }

                if (!Schema::hasColumn('academic_disciplines', 'seo_page_description')) {
                    $table->text('seo_page_description')->nullable()->after('seo_page_title');
                }

                if (!Schema::hasColumn('academic_disciplines', 'seo_page_h1_title')) {
                    $table->text('seo_page_h1_title')->nullable()->after('seo_page_description');
                }

                if (!Schema::hasColumn('academic_disciplines', 'seo_page_keywords')) {
                    $table->text('seo_page_keywords')->nullable()->after('seo_page_h1_title');
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
        if(Schema::hasTable('academic_disciplines')) {
            Schema::table('academic_disciplines', function (Blueprint $table) {
                if (Schema::hasColumn('academic_disciplines', 'seo_page_title')) {
                    $table->dropColumn('seo_page_title');
                }

                if (Schema::hasColumn('academic_disciplines', 'seo_page_description')) {
                    $table->dropColumn('seo_page_description');
                }

                if (Schema::hasColumn('academic_disciplines', 'seo_page_h1_title')) {
                    $table->dropColumn('seo_page_h1_title');
                }

                if (Schema::hasColumn('academic_disciplines', 'seo_page_keywords')) {
                    $table->dropColumn('seo_page_keywords');
                }
            });
        }
    }
}
