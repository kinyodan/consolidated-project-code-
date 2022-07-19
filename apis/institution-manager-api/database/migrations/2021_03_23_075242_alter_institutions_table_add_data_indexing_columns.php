<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterInstitutionsTableAddDataIndexingColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(Schema::hasTable('institutions')) {
            Schema::table('institutions', function (Blueprint $table) {
                if (!Schema::hasColumn('institutions', 'indexing_object_id')) {
                    $table->string('indexing_object_id')->nullable()->unique();
                }

                if (!Schema::hasColumn('institutions', 'has_updates')) {
                    $table->tinyInteger('has_updates')->default(1);
                }

                if (!Schema::hasColumn('institutions', 'is_picked_for_indexing')) {
                    $table->tinyInteger('is_picked_for_indexing')->default(1);
                }

                if (!Schema::hasColumn('institutions', 'time_picked_for_indexing')) {
                    $table->dateTime('time_picked_for_indexing')->nullable();
                }

                if (!Schema::hasColumn('institutions', 'time_taken_to_index')) {
                    $table->double('time_taken_to_index', 8, 2)->nullable();
                }

                if (!Schema::hasColumn('institutions', 'indexing_error')) {
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
        if(Schema::hasTable('institutions')) {
            Schema::table('institutions', function (Blueprint $table) {
                if (Schema::hasColumn('institutions', 'indexing_object_id')) {
                    $table->dropColumn('indexing_object_id');
                }

                if (Schema::hasColumn('institutions', 'has_updates')) {
                    $table->dropColumn('has_updates');
                }

                if (Schema::hasColumn('institutions', 'is_picked_for_indexing')) {
                    $table->dropColumn('is_picked_for_indexing');
                }

                if (Schema::hasColumn('institutions', 'time_picked_for_indexing')) {
                    $table->dropColumn('time_picked_for_indexing');
                }

                if (Schema::hasColumn('institutions', 'time_taken_to_index')) {
                    $table->dropColumn('time_taken_to_index');
                }

                if (Schema::hasColumn('institutions', 'indexing_error')) {
                    $table->dropColumn('indexing_error');
                }
            });
        }
    }
}
