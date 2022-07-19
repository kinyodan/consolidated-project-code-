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
    $router->get('/', [
        'as' => 'public-view-list-courses',
        'uses' => 'Courses\Queries\PublicView\SearchCourseQueryController@search'
    ]);

    $router->post('/lead/submit', [
        'middleware' => ['trimmer'],
        'as' => 'submit-a-new-lead',
        'uses' => 'PublicView\CoursesPublicViewController@submitLead'
    ]);

    $router->post('/lead/submit-progressive-lead-form', [
        'middleware' => ['trimmer'],
        'as' => 'submit-progressive-lead-form',
        'uses' => 'PublicView\CoursesPublicViewController@submitProgressiveLeadForm'
    ]);

    $router->get('/academic-disciplines', [
        'as' => 'get-academic-disciplines',
        'uses' => 'PublicView\CoursesPublicViewController@getAcademicDisciplines'
    ]);

    $router->get('/usd-exchange-rate', [
        'as' => 'get-usd-exchange-rate',
        'uses' => 'PublicView\CoursesPublicViewController@usdExchangeRate'
    ]);

    $router->get('/get-top-courses', [
        'as' => 'get-top-courses',
        'uses' => 'PublicView\CoursesPublicViewController@getTopCourses'
    ]);

    $router->post('/footer-menu', [
        'as' => 'get-footer-menu',
        'uses' => 'PublicView\CoursesPublicViewController@getFooterMenu'
    ]);

    $router->get('/countries/{country_code}', [
        'as' => 'get-courses-in-a-country',
        'uses' => 'PublicView\CoursesPublicViewController@course'
    ]);

    $router->get('/get-sitemap', [
        'as' => 'get-sitemap',
        'uses' => 'PublicView\CoursesPublicViewController@getSitemap'
    ]);

    $router->post('/add-user-mailing-list', [
        'as' => 'add-user-mailing-list',
        'uses' => 'PublicView\CoursesPublicViewController@subscribeUserToMailingList'
    ]);

    $router->get('/{course_code}', [
        'as' => 'view-single-course-details',
        'uses' => 'PublicView\CoursesPublicViewController@course'
    ]);

    $router->get('/{course_code}', [
        'as' => 'view-single-course-details',
        'uses' => 'PublicView\CoursesPublicViewController@course'
    ]);

    $router->post('/get-courses-per-discipline', [
        'as' => 'get-courses-per-discipline',
        'uses' => 'PublicView\CoursesPublicViewController@getCoursesPerDiscipline'
    ]);
});
