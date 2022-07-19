<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPushedToSearchEngineToSelectedKeyPhrasesTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    if (Schema::hasTable('selected_key_phrases')) {
      Schema::table('selected_key_phrases', function (Blueprint $table) {
        if (!Schema::hasColumn('selected_key_phrases', 'is_pushed_to_search_engine')) {
          $table->integer('is_pushed_to_search_engine')
            ->after('page_visits_associated_by_phrase')
            ->default(0);
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
    if (Schema::hasTable('selected_key_phrases')) {
      Schema::table('selected_key_phrases', function (Blueprint $table) {
        if (Schema::hasColumn('selected_key_phrases', 'is_pushed_to_search_engine')) {
          Schema::disableForeignKeyConstraints();
          $table->dropColumn('is_pushed_to_search_engine');
          Schema::enableForeignKeyConstraints();
        }
      });
    }
  }
}
