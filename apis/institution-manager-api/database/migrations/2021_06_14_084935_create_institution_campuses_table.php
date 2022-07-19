<?php
use App\Models\Institution;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInstitutionCampusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('institution_campuses')) {
            Schema::create('institution_campuses', function (Blueprint $table) {
                $table->charset = 'utf8mb4';
                $table->collation = 'utf8mb4_unicode_ci';
                $table->bigIncrements('id');
                $table->string('institution_code');
                $table->string('campus_name_slug');
                $table->string('campus_name');
                $table->text('campus_image');
                $table->text('campus_short_description');
                $table->text('campus_description');
                $table->point('campus_location_coordinates')->nullable();
                $table->string('campus_location_country')->nullable();
                $table->string('campus_location_city')->nullable();
                $table->text('campus_location_address')->nullable();
                $table->integer('display_order')->nullable();
                $table->tinyInteger('is_active')->default(1);
                $table->tinyInteger('is_deleted')->default(0);
                $table->string('created_by')->nullable();
                $table->string('updated_by')->nullable();
                $table->string('deleted_by')->nullable();
                $table->dateTimeTz('created_at')->nullable();
                $table->dateTimeTz('updated_at')->nullable();
                $table->dateTimeTz('deleted_at')->nullable();
                $table->unique(['institution_code', 'campus_name_slug'], 'unique_institution_campus');
                $table->foreign('institution_code')
                    ->references('institution_code')
                    ->on((new Institution())->getTable());
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
        if(Schema::hasTable('institution_campuses')){
            Schema::disableForeignKeyConstraints();
            Schema::dropIfExists('institution_campuses');
            Schema::enableForeignKeyConstraints();
        }
    }
}
