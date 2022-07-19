<?php
/** @var Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all the routes for an application.
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
    $router->group(['prefix' => 'rpc'], function () use ($router) {
        $router->get('/course-count/{institution_code}', [
            'as' => 'get-institution-course-count',
            'uses' => 'Courses\CourseController@count'
        ]);

        $router->get('/get-institution-course-categories/{institution_code}', [
            'as' => 'get-institution-course-categories',
            'uses' => 'Courses\CourseController@getInstitutionCourseCategories'
        ]);

        $router->post('/publish-courses-related-to-this-institution', [
            'as' => 'publish-courses-related-to-this-institution',
            'uses' => 'Courses\CourseController@publishRelatedCourseToInstitution'
        ]);

        $router->post('/unpublish-courses-related-to-this-institution', [
            'as' => 'unpublish-courses-related-to-this-institution',
            'uses' => 'Courses\CourseController@unPublishRelatedCourseToInstitution'
        ]);
        $router->post('/delete-courses-related-to-this-institution', [
            'as' => 'delete-courses-related-to-this-institution',
            'uses' => 'Courses\CourseController@deleteRelatedCourseToInstitution'
        ]);

        $router->get('/academic-disciplines', [
            'as' => 'get-academic-disciplines',
            'uses' => 'Courses\CourseController@getActiveAcademicDisciplines'
        ]);

        $router->get('/get-country-education-systems/{country_code}', [
            'as' => 'get-country-education-systems',
            'uses' => 'EducationTypes\EducationTypesController@getEducationTypesInCountryByCountryCode'
        ]);

        $router->post('get-courses-pathways', [
            'as' => 'get-courses-pathways',
            'uses' => 'CoursesPathways\CoursesPathwaysController@getCourseDisciplines'
        ]);

        $router->post('get-single-pathway-details', [
            'as' => 'get-single-pathway-details',
            'uses' => 'CoursesPathways\CoursesPathwaysController@getSingleCoursePathwayDetails'
        ]);

        $router->post('get-courses-subjects/{discipline_id}', [
            'as' => 'get-courses-subjects',
            'uses' => 'CoursesPathways\CoursesPathwaysController@getSubjectDiscipline'
        ]);

        $router->post('list-display-subjects/{discipline_id}', [
            'as' => 'list-display-subjects',
            'uses' => 'DisplaySubjects\DisplaySubjectsController@getSubjectsByDiscipline'
        ]);

        $router->post('list-courses-pathways', [
            'as' => 'list-courses-pathways',
            'uses' => 'CoursesPathways\CoursesPathwaysController@getCareerPathways'
        ]);
    });
});

$router->group(['prefix' => 'leads'], function () use ($router) {
    $router->group(['prefix' => 'rpc'], function () use ($router) {
        $router->get('/details/{email_address}', [
            'as' => 'get-lead-details-by-email-address',
            'uses' => 'Leads\Queries\GetLeadDetailsQueryController@detailsByEmailAddress'
        ]);

        $router->get('/details/from-crm-by-email/{email_address}', [
            'as' => 'get-lead-details-from-crm-by-email-address',
            'uses' => 'Leads\Queries\GetLeadDetailsQueryController@detailsByEmailAddressFromCRM'
        ]);

        $router->get('/details/from-crm-by-email/{email_address}', [
            'as' => 'get-lead-details-from-crm-by-email-address',
            'uses' => 'Leads\Queries\GetLeadDetailsQueryController@detailsByEmailAddressFromCRM'
        ]);

        $router->get('/details/from-crm-by-lead-id/{lead_id}', [
            'as' => 'get-lead-details-from-crm-by-lead-id',
            'uses' => 'Leads\Queries\GetLeadDetailsQueryController@detailsByLeadIDFromCRM'
        ]);

        $router->get('/details/from-crm-by-opportunity-id/{opportunity_id}', [
            'as' => 'get-lead-details-from-crm-by-lead-id',
            'uses' => 'Leads\Queries\GetLeadDetailsQueryController@detailsByOpportunityIDFromCRM'
        ]);

        $router->get('/details/by-lead-identifier/{lead_identifier}', [
            'as' => 'get-lead-id-from-db-by-lead-identifier',
            'uses' => 'Leads\Queries\GetLeadDetailsQueryController@detailsByLeadIdentifier'
        ]);

        $router->get('/details/get-student-details-from-crm-based-student-id/{student_id}', [
            'as' => 'get-student-details-from-crm-based-student-id',
            'uses' => 'Leads\Queries\GetLeadDetailsQueryController@getStudentDetailsFromCRMBasedStudentID'
        ]);

        $router->get('/details/get-opportunity-related-lists-from-crm-by-opportunity-id/{opportunity_id}', [
            'as' => 'get-opportunity-related-lists-from-crm-by-opportunity-id',
            'uses' => 'Leads\Queries\GetLeadDetailsQueryController@getOpportunityRelatedListsFromCRMByOpportunityID'
        ]);
    });
});
