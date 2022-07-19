<?php

use App\Http\Controllers\ManageSchools\ManageSchoolsController;
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


Route::group(['prefix' => 'schools', 'middleware' => ['localization', 'jwtauth', 'trimmer', 'throttle:' . $maximumRequestsPerMinute . ',1']], function () use ($maximumRequestsPerMinute) {
  Route::post('list-schools', [ManageSchoolsController::class, 'schools']);
  Route::post('add-school-details', [ManageSchoolsController::class, 'add']);
  Route::post('{school_id}/streams', [ManageSchoolsController::class, 'streams']);
  Route::post('{school_id}/show-school-curriculum', [ManageSchoolsController::class, 'curriculums']);
  Route::post('{school_id}/show-school-details', [ManageSchoolsController::class, 'show']);
  Route::post('{school_id}/update-school-details', [ManageSchoolsController::class, 'update']);
  Route::post('{school_id}/delete-school-details', [ManageSchoolsController::class, 'delete']);
  
});