<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterConstentNLinkedinProfileToInstitutionAlumni extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('institution_alumni')) {
            Schema::table('institution_alumni', function (Blueprint $table) {
                if (!Schema::hasColumn('institution_alumni', 'is_consented')) {
                    $table->tinyInteger('is_consented')->after('is_active')->nullable();
                }
                if (!Schema::hasColumn('institution_alumni', 'show_your_profile')) {
                    $table->tinyInteger('show_your_profile')->after('is_consented')->nullable();
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
        if (Schema::hasTable('institution_alumni')) {
            Schema::table('institution_alumni', function (Blueprint $table) {
                if (Schema::hasColumn('institution_alumni', 'is_consented')) {
                    $table->dropColumn('is_consented');
                }
                if (Schema::hasColumn('institution_alumni', 'show_your_profile')) {
                    $table->dropColumn('show_your_profile');
                }
            });
        }
    }
}
