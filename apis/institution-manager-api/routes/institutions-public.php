<?php
/** @var Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

use Laravel\Lumen\Routing\Router;

$maximumRequestsPerMinute = intval(config('craydle.system.throttle.default_throttle_limit'));
$maximumRequestsPerMinute = !empty($maximumRequestsPerMinute) ? intval($maximumRequestsPerMinute) : 10000;

$router->group(['prefix' => 'institutions','middleware' => ['localization', 'response', 'trimmer', 'throttle:'.$maximumRequestsPerMinute.',1']], function () use ($router, $maximumRequestsPerMinute) {
    $router->get('/get-countries-with-active-programs', [
        'as' => 'get-countries-with-active-programs',
        'uses' => 'PublicView\Queries\GetCountriesWithActiveProgramsQueryController@get'
    ]);

    $router->get('/get-countries-intakes/{country_code}', [
        'as' => 'get-countries-intakes-by-country-code',
        'uses' => 'PublicView\Queries\GetIntakesInCountryQueryController@get'
    ]);

    $router->get('/{institution_code}', [
        'as' => 'view-the-institution-details',
        'uses' => 'PublicView\Queries\SingleInstitutionQueryController@view'
    ]);
});
