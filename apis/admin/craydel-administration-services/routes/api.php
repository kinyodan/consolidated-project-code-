<?php

use App\Http\Controllers\ManageGraduationYears\ManageGraduationYearsController;
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


Route::group(['prefix' => 'years', 'middleware' => ['localization', 'jwtauth', 'trimmer', 'throttle:' . $maximumRequestsPerMinute . ',1']], function () use ($maximumRequestsPerMinute) {
  Route::post('list', [ManageGraduationYearsController::class, 'years']);
  Route::post('add-a-year', [ManageGraduationYearsController::class, 'add']);
  Route::post('{year_id}/show-a-year', [ManageGraduationYearsController::class, 'show']);
  Route::post('{year_id}/update-a-year', [ManageGraduationYearsController::class, 'update']);
  Route::post('{year_id}/delete-a-year', [ManageGraduationYearsController::class, 'delete']);
  
});

 




