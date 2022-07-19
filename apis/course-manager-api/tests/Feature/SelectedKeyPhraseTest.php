<?php

namespace Feature;

use App\Http\Controllers\Helpers\LanguageTranslationHelper;
use App\Models\SelectedKeyphrase;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Schema;
use TestCase;
use Illuminate\Support\Facades\Event;
use TestingHelperFunction;

class SelectedKeyPhraseTest extends TestCase
{
  use TestingHelperFunction;
  
  
  public function testIfExtractedKeyPhrasesCanBePushedToSearchEngine()
  {
    $this->withoutExceptionHandling();
    Schema::disableForeignKeyConstraints();
    DB::table((new SelectedKeyphrase())->getTable())->truncate();
    Schema::enableForeignKeyConstraints();
    $course=SelectedKeyphrase::factory()->count(1)->create();
    $this->artisan('bulk:push-key-phrases-to-search-engine');
    $this->seeInDatabase((new SelectedKeyphrase())->getTable(), [
      'course_code' => $course->first()->course_code,
      'is_pushed_to_search_engine' => 1,
    ]);
    Schema::disableForeignKeyConstraints();
    DB::table((new SelectedKeyphrase())->getTable())->truncate();
    Schema::enableForeignKeyConstraints();
  }
  
}
