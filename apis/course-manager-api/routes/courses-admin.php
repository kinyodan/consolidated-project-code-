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

$router->group(['prefix' => 'courses'], function () use ($router, $maximumRequestsPerMinute) {
    $router->group(['prefix' => 'admin', 'middleware' => ['jwt.auth', 'localization', 'response', 'trimmer', 'throttle:' . $maximumRequestsPerMinute . ',1']], function () use ($router) {
        $router->get('/', ['as' => 'list-courses', 'uses' => 'Courses\CourseController@courses']);
        $router->post('/', ['as' => 'list-courses', 'uses' => 'Courses\CourseController@courses']);
        $router->get('/build', ['as' => 'build-new-course-request', 'uses' => 'Courses\CourseController@build']);
        $router->post('/create', ['as' => 'create-new-course-request', 'uses' => 'Courses\CourseController@create']);
        $router->get('/{course_code}/edit', ['as' => 'edit-course', 'uses' => 'Courses\CourseController@edit']);
        $router->post('/{course_code}/update', ['as' => 'update-course-details', 'uses' => 'Courses\CourseController@update']);
        $router->post('/{course_code}/feature', ['as' => 'feature-course', 'uses' => 'Courses\CourseController@feature']);
        $router->post('/{course_code}/delete', ['as' => 'delete-course-details', 'uses' => 'Courses\CourseController@delete']);
        $router->post('/{course_code}/unpublish', ['as' => 'unpublish-course-details', 'uses' => 'Courses\CourseController@unpublish']);
        $router->post('/{course_code}/publish', ['as' => 'publish-course-details', 'uses' => 'Courses\CourseController@publish']);
        $router->post('/bulk-delete', ['as' => 'bulk-delete-course-details', 'uses' => 'Courses\CourseController@bulkDelete']);
        $router->post('/bulk-publish', ['as' => 'bulk-publish-course-details', 'uses' => 'Courses\CourseController@bulkPublish']);
        $router->post('/bulk-unpublish', ['as' => 'bulk-unpublish-course-details', 'uses' => 'Courses\CourseController@bulkUnpublish']);
        $router->post('/import', ['as' => 'import-courses', 'uses' => 'Courses\CourseController@import']);

        /**  Subjects Routes */
        $router->get('list-subjects', ['as' => 'list-subjects', 'uses' => 'Subjects\SubjectController@subjects']);
        $router->post('list-subjects', ['as' => 'list-subjects', 'uses' => 'Subjects\SubjectController@subjects']);
        $router->get('search-subjects', ['as' => 'search-subjects', 'uses' => 'Subjects\SubjectController@searchSubjects']);
        $router->post('create-subject', ['as' => 'create-new-subject-request', 'uses' => 'Subjects\SubjectController@create']);
        $router->get('{subject_id}/edit-subject', ['as' => 'edit-subject-request', 'uses' => 'Subjects\SubjectController@edit']);
        $router->post('{subject_id}/update-subject', ['as' => 'update-subject-request', 'uses' => 'Subjects\SubjectController@update']);
        $router->post('{subject_id}/delete-subject', ['as' => 'delete-subject-request', 'uses' => 'Subjects\SubjectController@delete']);

        /** Clusters Education Types Routes */
        $router->get('list-education-types', ['as' => 'list-education-types', 'uses' => 'EducationTypes\EducationTypesController@educations']);
        $router->post('list-education-types', ['as' => 'list-education-types', 'uses' => 'EducationTypes\EducationTypesController@educations']);
        $router->post('create-education-types', ['as' => 'create-new-education-types-request', 'uses' => 'EducationTypes\EducationTypesController@create']);
        $router->get('{education_type_id}/edit-education-type', ['as' => 'edit-education-type-request', 'uses' => 'EducationTypes\EducationTypesController@edit']);
        $router->post('{education_type_id}/update-education-type', ['as' => 'update-education-type-request', 'uses' => 'EducationTypes\EducationTypesController@update']);
        $router->post('{education_type_id}/delete-education-type', ['as' => 'delete-education-type-request', 'uses' => 'EducationTypes\EducationTypesController@delete']);

        /** Clusters  Routes */
        $router->get('list-clusters', ['as' => 'list-clusters', 'uses' => 'Clusters\ClusterController@clusters']);
        $router->post('list-clusters', ['as' => 'list-clusters', 'uses' => 'Clusters\ClusterController@clusters']);
        $router->post('create-cluster', ['as' => 'create-new-cluster-request', 'uses' => 'Clusters\ClusterController@create']);
        $router->get('{cluster_id}/edit-clusters', ['as' => 'edit-clusters-request', 'uses' => 'Clusters\ClusterController@edit']);
        $router->post('{cluster_id}/update-clusters', ['as' => 'update-clusters-request', 'uses' => 'Clusters\ClusterController@update']);
        $router->post('{cluster_id}/delete-clusters', ['as' => 'delete-clusters-request', 'uses' => 'Clusters\ClusterController@delete']);

        /** Clusters Subjects Routes */
        $router->get('build-clusters', ['as' => 'build-clusters', 'uses' => 'ClustersSubject\ClusterSubjectController@build']);
        $router->get('list-clusters-subject', ['as' => 'list-clusters-subject', 'uses' => 'ClustersSubject\ClusterSubjectController@getClusterSubject']);
        $router->post('list-clusters-subject', ['as' => 'list-clusters-subject', 'uses' => 'ClustersSubject\ClusterSubjectController@getClusterSubject']);
        $router->post('create-cluster-subject', ['as' => 'create-new-cluster-subject-request', 'uses' => 'ClustersSubject\ClusterSubjectController@create']);
        $router->get('{cluster_subject_id}/select-cluster-subject', ['as' => 'select-cluster-subject-request', 'uses' => 'ClustersSubject\ClusterSubjectController@edit']);
        $router->get('{cluster_subject_id}/edit-cluster-subject', ['as' => 'get-cluster-subject-request', 'uses' => 'ClustersSubject\ClusterSubjectController@edit']);
        $router->post('{cluster_subject_id}/update-cluster-subject', ['as' => 'update-cluster-subject-request', 'uses' => 'ClustersSubject\ClusterSubjectController@update']);
        $router->post('{cluster_subject_id}/delete-cluster-subject', ['as' => 'delete-cluster-subject-request', 'uses' => 'ClustersSubject\ClusterSubjectController@delete']);

        /** Grades Routes */
        $router->get('build-grades', ['as' => 'build-grades', 'uses' => 'Grades\GradeController@build']);
        $router->get('list-grades', ['as' => 'list-grades', 'uses' => 'Grades\GradeController@grades']);
        $router->post('list-grades', ['as' => 'list-grades', 'uses' => 'Grades\GradeController@grades']);
        $router->post('create-grades', ['as' => 'create-grades', 'uses' => 'Grades\GradeController@create']);
        $router->get('{country_id}/select-grades-country', ['as' => 'select-grades-country', 'uses' => 'Grades\GradeController@select']);
        $router->get('{grade_id}/select-grade', ['as' => 'select-grade', 'uses' => 'Grades\GradeController@selectGrade']);
        $router->post('{grade_id}/update-grade', ['as' => 'update-grade', 'uses' => 'Grades\GradeController@update']);
        $router->post('{grade_id}/delete-grade', ['as' => 'delete-grade', 'uses' => 'Grades\GradeController@delete']);


        /** courses pathways Routes */
        $router->get('build-courses-pathways', ['as' => 'build-courses-pathways', 'uses' => 'CoursesPathways\CoursesPathwaysController@build']);
        $router->get('list-courses-pathways', ['as' => 'list-courses-pathways', 'uses' => 'CoursesPathways\CoursesPathwaysController@pathways']);
        $router->post('list-courses-pathways', ['as' => 'list-courses-pathways', 'uses' => 'CoursesPathways\CoursesPathwaysController@pathways']);
        $router->post('create-courses-pathways', ['as' => 'create-courses-pathways', 'uses' => 'CoursesPathways\CoursesPathwaysController@create']);
        $router->get('get-courses-pathways', ['as' => 'get-courses-pathways', 'uses' => 'CoursesPathways\CoursesPathwaysController@getCourseDisciplines']);
        $router->get('{course_pathway_id}/edit-courses-pathways', ['as' => 'edit-courses-pathways', 'uses' => 'CoursesPathways\CoursesPathwaysController@edit']);
        $router->post('{course_pathway_id}/update-courses-pathways', ['as' => 'update-courses-pathways', 'uses' => 'CoursesPathways\CoursesPathwaysController@update']);
        $router->post('{course_pathway_id}/delete-courses-pathways', ['as' => 'delete-courses-pathways', 'uses' => 'CoursesPathways\CoursesPathwaysController@delete']);

        /** Subjects Display  Routes */
        $router->post('create-display-subjects', ['as' => 'create-display-subjects', 'uses' => 'DisplaySubjects\DisplaySubjectsController@create']);
        $router->post('list-display-subjects', ['as' => 'list-display-subjects', 'uses' => 'DisplaySubjects\DisplaySubjectsController@subjects']);
        $router->get('list-display-subjects', ['as' => 'list-display-subjects', 'uses' => 'DisplaySubjects\DisplaySubjectsController@subjects']);
        $router->get('{display_subject_id}/select-display-subjects', ['as' => 'select-display-subjects', 'uses' => 'DisplaySubjects\DisplaySubjectsController@select']);
        $router->post('{display_subject_id}/update-display-subjects', ['as' => 'updated-display-subjects', 'uses' => 'DisplaySubjects\DisplaySubjectsController@update']);
        $router->post('{display_subject_id}/delete-display-subjects', ['as' => 'delete-display-subjects', 'uses' => 'DisplaySubjects\DisplaySubjectsController@delete']);

        /** Key Phrases Routes*/
        $router->post('create-course-key-phrases', ['as' => 'create-course-key-phrases', 'uses' => 'KeyPhrases\KeyPhrasesController@create']);
        $router->post('get-course-by-enrolled-date', ['as' => 'get-course-by-enrolled-date', 'uses' => 'GenerateEnrollmentDetails\GenerateEnrollmentDetailsController@getCoursesByEnrollmentDates']);

    });
});
