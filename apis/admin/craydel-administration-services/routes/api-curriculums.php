<?php

use App\Http\Controllers\ManageCurriculums\ManageCurriculumsController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


$maximumRequestsPerMinute = intval(config('app.system.throttle.default_throttle_limit'));
$maximumRequestsPerMinute = !empty($maximumRequestsPerMinute) ? $maximumRequestsPerMinute : 10000;

Route::group(['prefix' => 'curriculums', 'middleware' => ['localization', 'jwtauth', 'trimmer', 'throttle:' . $maximumRequestsPerMinute . ',1']], function () use ($maximumRequestsPerMinute) {
  Route::post('list', [ManageCurriculumsController::class, 'curriculums']);
  Route::post('add-a-curriculum', [ManageCurriculumsController::class, 'add']);
  Route::post('{curriculum_id}/show-a-curriculum', [ManageCurriculumsController::class, 'show']);
  Route::post('{curriculum_id}/update-a-curriculum', [ManageCurriculumsController::class, 'update']);
  Route::post('{curriculum_id}/delete-a-curriculum', [ManageCurriculumsController::class, 'delete']);
  Route::post('{curriculum_id}/show-a-curriculum-class', [ManageCurriculumsController::class, 'classes']);
  
});