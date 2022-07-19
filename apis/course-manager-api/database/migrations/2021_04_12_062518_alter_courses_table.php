<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterCoursesTable extends Migration
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
                if(Schema::hasColumn('courses', 'time_picked_for_indexing')){
                    $table->string('learning_mode')->nullable()->change();
                }

                if (Schema::hasColumn('courses', 'learning_mode')) {
                    $table->string('learning_mode')->nullable()->change();
                }

                if (Schema::hasColumn('courses', 'discipline_code')) {
                    $table->string('discipline_code')->nullable()->change();
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
    {}
}
