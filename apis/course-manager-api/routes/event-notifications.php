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

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$maximumRequestsPerMinute = intval(config('craydle.system.throttle.default_throttle_limit'));
$maximumRequestsPerMinute = !empty($maximumRequestsPerMinute) ? intval($maximumRequestsPerMinute) : 10000;

$router->group(['prefix' => 'courses'], function () use ($router) {
    $router->group(['prefix' => 'events'], function () use ($router) {
        $router->get('/opportunity-updated', [
            'as' => 'listen-to-opportunity-update-events',
            'uses' => 'Application\Commands\Workflow\Opportunity\OpportunityWorkflowCommandController@receive'
        ]);

        $router->post('/lead-updates', [
            'as' => 'listen-to-lead-update-or-creation',
            'uses' => 'Application\Commands\Workflow\Leads\LeadWorkflowCommandController@receive'
        ]);
    });
});
