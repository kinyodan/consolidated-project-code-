<?php

use App\Http\Controllers\ManageClasses\ManageClassesController;
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


Route::group(['prefix' => 'classes', 'middleware' => ['localization', 'jwtauth', 'trimmer', 'throttle:' . $maximumRequestsPerMinute . ',1']], function () use ($maximumRequestsPerMinute) {
  Route::post('build', [ManageClassesController::class, 'build']);
  Route::post('list-classes', [ManageClassesController::class, 'classes']);
  Route::post('add-a-class', [ManageClassesController::class, 'add']);
  Route::post('{class_id}/show-a-class', [ManageClassesController::class, 'show']);
  Route::post('{class_id}/update-a-class', [ManageClassesController::class, 'update']);
  Route::post('{class_id}/delete-a-class', [ManageClassesController::class, 'delete']);
  
});