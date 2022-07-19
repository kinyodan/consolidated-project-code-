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

$router->post('receive-user-created-notification','EventsController@userCreated');
$router->post('receive-user-activated-notification','EventsController@userActivated');
$router->post('receive-user-assessment-complete-notification','EventsController@userHasTakenAssessment');
$router->post('receive-user-assessment-subscribed-notification','EventsController@userHasSubscribedToAssessment');
$router->post('receive-user-higher-learning-update-notification','EventsController@userAppliedForHigherLearning');
