<?php

use App\Http\Controllers\ManageSchoolAdmin\ManageSchoolAdmin;
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
  Route::post('{school_code}/school-admins', [ManageSchoolAdmin::class, 'admins']);
  Route::post('{school_code}/school-admins/add', [ManageSchoolAdmin::class, 'add']);
  Route::post('{school_code}/school-admins/{school_admin_id}/show', [ManageSchoolAdmin::class, 'show']);
  Route::post('{school_code}/school-admins/{school_admin_id}/update', [ManageSchoolAdmin::class, 'update']);
  Route::post('{school_code}/school-admins/{school_admin_id}/delete', [ManageSchoolAdmin::class, 'delete']);
  
});