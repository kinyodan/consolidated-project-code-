<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInstitutionGalleryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('institution_gallery')) {
            Schema::create('institution_gallery', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('institution_code');
                $table->string('asset_name_slug');
                $table->string('asset_name')->nullable();
                $table->text('asset_description')->nullable();
                $table->integer('asset_position')->default(1);
                $table->tinyInteger('is_featured')->default(0);
                $table->string('asset_code')->nullable();
                $table->enum('type', ['Image', 'VideoLink'])->nullable();
                $table->text('temp_image_path')->nullable();
                $table->text('small_image_url')->nullable();
                $table->text('medium_image_url')->nullable();
                $table->text('big_image_url')->nullable();
                $table->text('video_url')->nullable();
                $table->tinyInteger('is_deleted')->default(0);
                $table->string('created_by')->nullable();
                $table->string('updated_by')->nullable();
                $table->string('deleted_by')->nullable();
                $table->dateTime('created_at')->nullable();
                $table->dateTime('update_at')->nullable();
                $table->dateTime('deleted_at')->nullable();
                $table->unique(['institution_code', 'asset_name_slug'], 'unique_inst_gallery_asset');
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
        if(Schema::hasTable('institution_gallery')){
            Schema::disableForeignKeyConstraints();
            Schema::dropIfExists('institution_gallery');
            Schema::enableForeignKeyConstraints();
        }
    }
}
