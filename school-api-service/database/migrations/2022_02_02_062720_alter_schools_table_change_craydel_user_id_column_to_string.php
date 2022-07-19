<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterSchoolsTableChangeCraydelUserIdColumnToString extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(Schema::hasTable('school_admins')) {
            Schema::table('school_admins', function (Blueprint $table) {
                if(Schema::hasColumn('school_admins','craydel_user_id')) {
                    $table->string('craydel_user_id')->nullable()->change();
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
        Schema::table('school_admins', function (Blueprint $table) {
            if(Schema::hasColumn('school_admins','craydel_user_id')) {
                //nothing to do
            }
        });
    }
}
