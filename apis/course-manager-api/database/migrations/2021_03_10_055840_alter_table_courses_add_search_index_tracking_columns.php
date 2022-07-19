<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableCoursesAddSearchIndexTrackingColumns extends Migration
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
                if(!Schema::hasColumn('courses','popularity')){
                    $table->float('popularity')->default('0.1');
                }

                if(!Schema::hasColumn('courses','indexing_object_id')){
                    $table->string('indexing_object_id')->unique()->nullable();
                }

                if(!Schema::hasColumn('courses','requires_indexing')){
                    $table->tinyInteger('requires_indexing')->default(0)->after('indexing_object_id');
                }

                if(!Schema::hasColumn('courses','has_updates')){
                    $table->tinyInteger('has_updates')->default(1);
                }

                if(!Schema::hasColumn('courses','is_picked_for_indexing')){
                    $table->tinyInteger('is_picked_for_indexing')->default(0);
                }

                if(!Schema::hasColumn('courses','time_picked_for_indexing')){
                    $table->dateTime('time_picked_for_indexing')->nullable();
                }

                if(!Schema::hasColumn('courses','time_taken_to_index')){
                    $table->float('time_taken_to_index')->nullable();
                }

                if(!Schema::hasColumn('courses','indexing_error')){
                    $table->text('indexing_error')->nullable();
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
                if(Schema::hasColumn('courses','indexing_object_id')){
                    $table->dropColumn('indexing_object_id');
                }

                if(Schema::hasColumn('courses','requires_indexing')){
                    $table->dropColumn('requires_indexing');
                }

                if(!Schema::hasColumn('courses','has_updates')){
                    $table->tinyInteger('has_updates')->default(1);
                }

                if(Schema::hasColumn('courses','is_picked_for_indexing')){
                    $table->dropColumn('is_picked_for_indexing');
                }

                if(Schema::hasColumn('courses','time_picked_for_indexing')){
                    $table->dropColumn('time_picked_for_indexing');
                }

                if(Schema::hasColumn('courses','time_taken_to_index')){
                    $table->dropColumn('time_taken_to_index');
                }

                if(Schema::hasColumn('courses','indexing_error')){
                    $table->dropColumn('indexing_error');
                }
            });
        }
    }
}
