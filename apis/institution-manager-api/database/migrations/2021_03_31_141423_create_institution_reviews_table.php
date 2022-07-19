<?php
use App\Models\Institution;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInstitutionReviewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('institution_reviews')) {
            Schema::create('institution_reviews', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('institution_code');
                $table->string('rated_by_email');
                $table->string('rated_by')->nullable();
                $table->text('course_taken')->nullable();
                $table->string('graduation_year')->nullable();
                $table->float('rating_score')->nullable();
                $table->text('review')->nullable();
                $table->tinyInteger('is_published')->default(1);
                $table->dateTime('published_on')->nullable();
                $table->integer('number_of_flags')->default(0);
                $table->string('created_by')->nullable();
                $table->string('updated_by')->nullable();
                $table->string('deleted_by')->nullable();
                $table->string('unpublished_by')->nullable();
                $table->dateTime('created_at')->nullable();
                $table->dateTime('updated_at')->nullable();
                $table->dateTime('deleted_at')->nullable();
                $table->dateTime('unpublished_on')->nullable();
                $table->unique(['institution_code', 'rated_by_email'], 'unique_user_institution_review');
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
        if(Schema::hasTable('institution_reviews')){
            Schema::disableForeignKeyConstraints();
            Schema::dropIfExists('institution_reviews');
            Schema::enableForeignKeyConstraints();
        }
    }
}
