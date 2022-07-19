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

$router->group(['prefix' => 'institutions'], function () use ($router, $maximumRequestsPerMinute) {
    $router->group(['prefix' => 'rpc', 'middleware' => ['localization', 'response', 'trimmer', 'throttle:'.$maximumRequestsPerMinute.',1']], function () use ($router) {
        $router->get('/get-summary/{institution_code}', [
            'as' => 'get-institution-summary',
            'uses' => 'Administration\Queries\GetSummaryDetailsQueryController@summary'
        ]);

        $router->get('/institutions-list', [
            'as' => 'get-institutions-list',
            'uses' => 'Administration\Queries\ListInstitutionsQueryController@institutions'
        ]);

        $router->get('/institution-names', [
            'as' => 'get-institution-names',
            'uses' => 'Administration\Queries\ListInstitutionsQueryController@activeInstitutionNames'
        ]);

        $router->get('/questions-list', [
            'as' => 'get-questions-list',
            'uses' => 'Administration\Queries\ListInstitutionsQueryController@questions'
        ]);

        $router->get('/currencies', [
            'as' => 'get-currencies',
            'uses' => 'Administration\Queries\ListInstitutionsQueryController@currencies'
        ]);

        $router->get('/countries', [
            'as' => 'get-countries',
            'uses' => 'Administration\Queries\ListInstitutionsQueryController@countries'
        ]);

        $router->get('/get-institution-details-by-name/{institution_name}', [
            'as' => 'get-institution-details-by-name',
            'uses' => 'Administration\Queries\ListInstitutionsQueryController@getInstitutionDetailsByName'
        ]);

        $router->post('/get-alumni-details-slug', [
            'as' => 'get-alumni-details-slug',
            'uses' => 'Administration\InstitutionController@getAlumniBySlug'
        ]);

        $router->post('/alumnus-submit-question-response', [
            'as' => 'alumnus-submit-question-response',
            'uses' => 'Administration\InstitutionController@submitQuestionRespond'
        ]);
        $router->post('/alumni-submit-review', [
            'as' => 'alumni-submit-review',
            'uses' => 'Administration\InstitutionController@alumniSubmitReview'
        ]);
        $router->post('/alumni-update-profile/{alumni_id}', [
            'as' => 'alumni-update-profile',
            'uses' => 'Administration\InstitutionController@updateAlumniProfile'
        ]);
        $router->post('/get-alumni-responses', [
            'as' => 'get-alumni-responses',
            'uses' => 'Administration\InstitutionController@getAlumniResponses'
        ]);
        $router->post('/get-alumni-reviews', [
            'as' => 'get-alumni-responses',
            'uses' => 'Administration\InstitutionController@getAlumniReviews'
        ]);

        $router->get('/get-alumni-questions/{alumni_id}', [
            'as' => 'get-alumni-questions',
            'uses' => 'Administration\InstitutionController@getAlumniQuestions'
        ]);
    });
});
