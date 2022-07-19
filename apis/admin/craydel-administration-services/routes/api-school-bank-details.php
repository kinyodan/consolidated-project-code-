<?php

use App\Http\Controllers\ManageSchoolBankDetails\ManageSchoolBankDetailsController;
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
  Route::post('{school_code}/bank-details', [ManageSchoolBankDetailsController::class, 'details']);
  Route::post('{school_code}/bank-details/add', [ManageSchoolBankDetailsController::class, 'add']);
  Route::post('{school_code}/bank-details/{bank_detail_id}/show', [ManageSchoolBankDetailsController::class, 'show']);
  Route::post('{school_code}/bank-details/{bank_detail_id}/update', [ManageSchoolBankDetailsController::class, 'update']);
  Route::post('{school_code}/bank-details/{bank_detail_id}/delete', [ManageSchoolBankDetailsController::class, 'delete']);
  
});