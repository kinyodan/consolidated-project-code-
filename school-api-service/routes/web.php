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


//school routes
$router->get('schools','SchoolsController@index');
$router->post('schools','SchoolsController@store');
$router->get('schools/{id}','SchoolsController@show');

//school admins routes
$router->get('school/admins','SchoolAdminsController@index');
$router->post('school/admins','SchoolAdminsController@store');
$router->get('school/admins/{id}','SchoolAdminsController@show');
$router->get('school/get-student-details/{student_email}', 'SchoolsController@studentDetails');

//school students routes
$router->group(['middleware' => 'jwtauth'], function () use ($router) {
    $router->get('school-details','SchoolAdminsController@getSchoolDetails');
    $router->get('students-build', 'StudentsController@build');
    $router->get('students', 'StudentsController@index');
    $router->post('students', 'StudentsController@store');
    $router->get('students/{id}', 'StudentsController@show');
    $router->post('students-resend-invite', 'StudentsController@resendInvite');
    $router->post('students-delete', 'StudentsController@deleteStudents');
    $router->post('import-student-list', 'BulkImportStudentInvitesController@import');
});
