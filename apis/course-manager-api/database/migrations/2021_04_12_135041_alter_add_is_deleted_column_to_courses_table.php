<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterAddIsDeletedColumnToCoursesTable extends Migration
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
                if(!Schema::hasColumn('courses', 'is_deleted')){
                    $table->tinyInteger('is_deleted')->default(0)->after('is_featured');
                }

                if(!Schema::hasColumn('courses', 'is_active')){
                    $table->tinyInteger('is_active')->default(1)->after('is_deleted');
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
                if(Schema::hasColumn('courses', 'is_deleted')){
                    $table->dropColumn('is_deleted');
                }

                if(Schema::hasColumn('courses', 'is_active')){
                    $table->dropColumn('is_active');
                }
            });
        }
    }
}
