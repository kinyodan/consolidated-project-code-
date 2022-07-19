<?php

namespace App\Http\Controllers\KeyPhrases\Commands;

use App\Http\Controllers\KeyPhrases\KeyPhrasesController;
use App\Http\Controllers\CraydelTypes\CraydelJSONResponseType;
use App\Http\Controllers\Helpers\LanguageTranslationHelper;
use App\Http\Middleware\Response;
use App\Models\Course;
use App\Models\Grade;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;
use App\Models\SelectedKeyphrase;

class CreateKeyPhrasesCommandController
{
  
  
  public KeyPhrasesController $keyPhrasesController;
  
  public function __construct(KeyPhrasesController $keyPhrasesController)
  {
    $this->keyPhrasesController = $keyPhrasesController;
  }
  public static function pushExtractedKeyPhrases($phrase)
  {
    
    $search_engine_url = config('services.craydel_services.search_engine.search_engine_url');
    $response = Http::asForm()->post($search_engine_url, [
      'course_code' => $phrase->course_code,
      'phrases' => $phrase->phrases]);
    if ($response) {
      DB::table((new SelectedKeyphrase())->getTable())->where('course_code', trim($phrase->course_code))->update([
        'is_pushed_to_search_engine' => 1,
        'updated_at' => Carbon::now()->toDateTimeString(),
      ]);
      
      
    }
  }
  
  public static function selectedCourse($course)
  {
    $key_phrases_extractor_url = config('services.craydel_services.key_phrases_extractor.key_phrases_extractor_url');
    $response = Http::asForm()->post($key_phrases_extractor_url, [
      'course_code' => $course->course_code,
      'course_overview' => $course->course_overview]);
    
    $response = json_decode($response->body());
    if ($response->status) {
      $course_code = $response->data->course_code;
      $phrases = $response->data->selected_keyphrases;
      foreach ($phrases as $phrase) {
        DB::table((new SelectedKeyphrase())->getTable())->insertOrIgnore([
          'course_code' => $course_code,
          'phrases' => $phrase,
          'created_at' => Carbon::now()->toDateTimeString()
        ]);
      }
      
    }
    
  }
  
  
}

