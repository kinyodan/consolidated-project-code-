<?php

use App\Http\Controllers\ManageStreams\ManageStreamController;
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


Route::group(['prefix' => 'school', 'middleware' => ['localization', 'jwtauth', 'trimmer', 'throttle:' . $maximumRequestsPerMinute . ',1']], function () use ($maximumRequestsPerMinute) {
  Route::post('{school_code}/streams', [ManageStreamController::class, 'streams']);
  Route::post('{school_code}/streams/add', [ManageStreamController::class, 'add']);
  Route::post('{school_code}/streams/{stream_id}/show', [ManageStreamController::class, 'show']);
  Route::post('{school_code}/streams/{stream_id}/update', [ManageStreamController::class, 'update']);
  Route::post('{school_code}/streams/{stream_id}/delete', [ManageStreamController::class, 'delete']);
  
});