<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSchoolAdminsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('school_admins')) {
            Schema::create('school_admins', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('craydel_user_id')->nullable();
                $table->foreignId('school_id');
                $table->string('admin_name', 500);
                $table->string('admin_email', 500)->unique();
                $table->string('admin_phone', 500)->unique();
                $table->text('admin_address')->nullable();
                $table->boolean('is_craydel_account_created')->default(0);
                $table->boolean('is_invite_sent')->default(0);
                $table->boolean('is_account_activated')->default(0);
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
        Schema::dropIfExists('school_admins');
    }
}
