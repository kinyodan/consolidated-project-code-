<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInstitutionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('institutions')){
            Schema::create('institutions', function (Blueprint $table) {
                $table->charset = 'utf8mb4';
                $table->collation = 'utf8mb4_unicode_ci';
                $table->bigIncrements('id');
                $table->string('country_code', 50);
                $table->string('institution_name_slug', 255);
                $table->string('institution_code', 50)->nullable()->unique();
                $table->string('institution_name')->nullable();
                $table->text('description')->nullable();
                $table->text('profile_details')->nullable();
                $table->tinyInteger('is_deleted')->default(0);
                $table->tinyInteger('is_active')->default(1);
                $table->string('created_by')->nullable();
                $table->string('updated_by')->nullable();
                $table->string('approved_by')->nullable();
                $table->string('deleted_by')->nullable();
                $table->dateTimeTz('created_at')->nullable();
                $table->dateTimeTz('updated_at')->nullable();
                $table->dateTimeTz('approved_at')->nullable();
                $table->dateTimeTz('deleted_at')->nullable();
                //$table->unique(['country_code', 'institution_name_slug'], 'unique_institution');
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
        if(Schema::hasTable('institutions')){
            Schema::disableForeignKeyConstraints();
            Schema::dropIfExists('institutions');
            Schema::enableForeignKeyConstraints();
        }
    }
}
