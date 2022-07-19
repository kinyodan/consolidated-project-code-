<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddExtraColumnsToInstitutionAlumniTable extends Migration
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
                if (!Schema::hasColumn('institution_alumni', 'unique_url')) {
                    $table->text('unique_url')->after('is_deleted')->nullable();
                }
                if (!Schema::hasColumn('institution_alumni', 'is_finished')) {
                    $table->tinyInteger('is_finished')->after('unique_url')->nullable();
                }
                if (!Schema::hasColumn('institution_alumni', 'invitation_code')) {
                    $table->text('invitation_code')->after('is_finished')->nullable();
                }
                if (!Schema::hasColumn('institution_alumni', 'status')) {
                    $table->tinyInteger('status')->after('invitation_code')->nullable();
                }
                if (!Schema::hasColumn('institution_alumni', 'question_step')) {
                    $table->tinyInteger('question_step')->after('status')->nullable();
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
                if (Schema::hasColumn('institution_alumni', 'unique_url')) {
                    $table->dropColumn('unique_url');
                }
                if (Schema::hasColumn('institution_alumni', 'is_finished')) {
                    $table->dropColumn('is_finished');
                }
                if (Schema::hasColumn('institution_alumni', 'invitation_code')) {
                    $table->dropColumn('invitation_code');
                }
                if (Schema::hasColumn('institution_alumni', 'status')) {
                    $table->dropColumn('status');
                }
                if (Schema::hasColumn('institution_alumni', 'question_step')) {
                    $table->dropColumn('question_step');
                }
            });
        }
    }
}
