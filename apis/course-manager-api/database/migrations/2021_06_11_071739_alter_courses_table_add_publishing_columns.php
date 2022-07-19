<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterCoursesTableAddPublishingColumns extends Migration
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
                if (!Schema::hasColumn($table->getTable(), 'is_published')) {
                    $table->tinyInteger('is_published')->default(0)->after('is_active');
                }

                if (!Schema::hasColumn($table->getTable(), 'should_unpublish')) {
                    $table->tinyInteger('should_unpublish')->default(0)->after('is_published');
                }

                if (!Schema::hasColumn($table->getTable(), 'is_picked_for_unpublishing')) {
                    $table->tinyInteger('is_picked_for_unpublishing')->default(0)->after('should_unpublish');
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
                if (Schema::hasColumn($table->getTable(), 'is_published')) {
                    $table->dropColumn('is_published');
                }

                if (Schema::hasColumn($table->getTable(), 'should_unpublish')) {
                    $table->dropColumn('should_unpublish');
                }

                if (Schema::hasColumn($table->getTable(), 'is_picked_for_unpublishing')) {
                    $table->dropColumn('is_picked_for_unpublishing');
                }
            });
        }
    }
}
