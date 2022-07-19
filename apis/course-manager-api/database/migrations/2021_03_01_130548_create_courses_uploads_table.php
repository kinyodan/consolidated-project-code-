<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCoursesUploadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('course_uploads')) {
            Schema::create('course_uploads', function (Blueprint $table) {
                $table->id();
                $table->longText('file_data')->nullable(false);
                $table->boolean('is_processed')->default(0);
                $table->integer('total_records')->default(0);
                $table->integer('successful_records')->default(0);
                $table->integer('failed_records')->default(0);
                $table->longText('failure_reasons')->nullable();
                $table->string('user_email')->nullable();
                $table->timestamps();
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
        if(Schema::hasTable('course_uploads')) {
            Schema::disableForeignKeyConstraints();
            Schema::dropIfExists('course_uploads');
            Schema::enableForeignKeyConstraints();
        }
    }
}
