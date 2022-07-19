<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddInFeaturedColumnToTheCoursesTable extends Migration
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
                if(!Schema::hasColumn('courses', 'is_featured')){
                    $table->tinyInteger('is_featured')->default(0)->after('course_rating');
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
                if(Schema::hasColumn('courses', 'is_featured')){
                    $table->dropColumn('is_featured');
                }
            });
        }
    }
}
