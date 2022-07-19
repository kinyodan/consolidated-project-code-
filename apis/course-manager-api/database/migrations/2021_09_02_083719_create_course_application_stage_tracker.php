<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCourseApplicationStageTracker extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('course_application_stage_tracker')){
            Schema::create('course_application_stage_tracker', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('opportunity_id');
                $table->string('current_stage');
                $table->string('next_stage');
                $table->tinyInteger('student_notification_sent')->default(0);
                $table->tinyInteger('is_processed')->default(0);
                $table->tinyInteger('is_picked_for_processing')->default(0);
                $table->dateTime('created_at');
                $table->dateTime('updated_at')->nullable();
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
        if(Schema::hasTable('course_application_stage_tracker')) {
            Schema::disableForeignKeyConstraints();
            Schema::dropIfExists('course_application_stage_tracker');
            Schema::enableForeignKeyConstraints();
        }
    }
}
