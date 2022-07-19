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
    $router->group(['prefix' => 'admin', 'middleware' => ['jwt.auth', 'localization', 'response', 'trimmer', 'throttle:' . $maximumRequestsPerMinute . ',1']], function () use ($router) {
        $router->get('/', [
            'as' => 'list-institutions',
            'uses' => 'Administration\InstitutionController@institutions'
        ]);
        $router->post('/', [
            'as' => 'list-institutions',
            'uses' => 'Administration\InstitutionController@institutions'
        ]);
        $router->get('/build', [
            'as' => 'build-institutions',
            'uses' => 'Administration\InstitutionController@build'
        ]);
        $router->post('/create', [
            'as' => 'create-institution',
            'uses' => 'Administration\InstitutionController@create'
        ]);
        $router->post('/upload', [
            'as' => 'bulk-upload-institutions',
            'uses' => 'Administration\InstitutionController@bulkUpload'
        ]);
        $router->get('/{institution_code}/edit', [
            'as' => 'show-institution-details',
            'uses' => 'Administration\InstitutionController@edit'
        ]);
        $router->post('/{institution_code}/update', [
            'as' => 'update-institution-details',
            'uses' => 'Administration\InstitutionController@update'
        ]);
        $router->post('/{institution_code}/review', [
            'as' => 'review-institution',
            'uses' => 'Administration\InstitutionController@review'
        ]);
        $router->post('/{institution_code}/feature', [
            'as' => 'feature-institution',
            'uses' => 'Administration\InstitutionController@feature'
        ]);
        $router->get('/{institution_code}/gallery', [
            'as' => 'get-the-institution-gallery',
            'uses' => 'Administration\InstitutionController@getInstitutionGallery'
        ]);
        $router->post('/{institution_code}/gallery/add', [
            'as' => 'add-item-to-institution-gallery',
            'uses' => 'Administration\InstitutionController@addGalleryItem'
        ]);
        $router->post('/{institution_code}/gallery/asset/{asset_code}/delete', [
            'as' => 'delete-institution-gallery-asset',
            'uses' => 'Administration\InstitutionController@deleteGalleryItem'
        ]);
        $router->post('/{institution_code}/gallery/asset/{asset_code}/feature', [
            'as' => 'feature-institution-gallery-asset',
            'uses' => 'Administration\InstitutionController@featureGalleryItem'
        ]);
        $router->get('/{institution_code}/accreditations', [
            'as' => 'institution-get-accreditations',
            'uses' => 'Administration\InstitutionController@getAccreditations'
        ]);
        $router->get('/{institution_code}/accreditations/{accreditation_id}', [
            'as' => 'institution-get-accreditation-details',
            'uses' => 'Administration\InstitutionController@getAccreditation'
        ]);
        $router->post('/{institution_code}/accreditations/add', [
            'as' => 'institution-add-accreditation',
            'uses' => 'Administration\InstitutionController@addAccreditation'
        ]);
        $router->post('/{institution_code}/accreditations/{accreditation_id}/update', [
            'as' => 'institution-update-accreditation',
            'uses' => 'Administration\InstitutionController@updateAccreditation'
        ]);
        $router->post('/{institution_code}/accreditations/{accreditation_id}/delete', [
            'as' => 'institution-delete-accreditation',
            'uses' => 'Administration\InstitutionController@deleteAccreditation'
        ]);;
        $router->get('/{institution_code}/alumni', [
            'as' => 'institution-get-alumni',
            'uses' => 'Administration\InstitutionController@getAlumni'
        ]);
        $router->get('/{institution_code}/alumni/build', [
            'as' => 'institution-build-alumnus',
            'uses' => 'Administration\InstitutionController@buildAlumnus'
        ]);
        $router->post('/{institution_code}/alumni/add', [
            'as' => 'institution-add-alumnus',
            'uses' => 'Administration\InstitutionController@addAlumnus'
        ]);
        $router->get('/{institution_code}/alumni/{alumnus_id}', [
            'as' => 'institution-get-alumnus-details',
            'uses' => 'Administration\InstitutionController@getAlumnus'
        ]);
        $router->post('/{institution_code}/alumni/{alumnus_id}/update', [
            'as' => 'institution-update-alumnus',
            'uses' => 'Administration\InstitutionController@updateAlumnus'
        ]);
        $router->post('/{institution_code}/alumni/{alumnus_id}/delete', [
            'as' => 'institution-delete-alumnus',
            'uses' => 'Administration\InstitutionController@deleteAlumnus'
        ]);
        $router->get('/{institution_code}/highlights', [
            'as' => 'institution-get-highlights',
            'uses' => 'Administration\InstitutionController@highlights'
        ]);
        $router->get('/{institution_code}/highlights/{highlight_id}', [
            'as' => 'institution-get-highlight-details',
            'uses' => 'Administration\InstitutionController@highlight'
        ]);
        $router->post('/{institution_code}/highlights/add', [
            'as' => 'institution-add-highlight-details',
            'uses' => 'Administration\InstitutionController@addHighlight'
        ]);
        $router->post('/{institution_code}/highlights/{highlight_id}/update', [
            'as' => 'institution-update-highlight-details',
            'uses' => 'Administration\InstitutionController@updateHighlight'
        ]);
        $router->post('/{institution_code}/highlights/{highlight_id}/delete', [
            'as' => 'institution-delete-highlight-details',
            'uses' => 'Administration\InstitutionController@deleteHighlight'
        ]);
        $router->post('/{institution_code}/publish', [
            'as' => 'publish-institution-details',
            'uses' => 'Administration\InstitutionController@publish'
        ]);
        $router->post('/{institution_code}/unpublish', [
            'as' => 'unpublish-institution-details',
            'uses' => 'Administration\InstitutionController@unpublish'
        ]);
        $router->post('/{institution_code}/delete', [
            'as' => 'delete-institution-details',
            'uses' => 'Administration\InstitutionController@delete'
        ]);
        $router->post('/alumni-upload', [
            'as' => 'bulk-alumni-upload',
            'uses' => 'Administration\InstitutionController@bulkAlumniUpload'
        ]);
        $router->post('/create-question-category', [
            'as' => 'create-question-category',
            'uses' => 'Administration\InstitutionController@createQuestionCategory'
        ]);
        $router->post('/create-question', [
            'as' => 'create-question',
            'uses' => 'Administration\InstitutionController@createQuestion'
        ]);
        $router->post('/alumni-list', [
            'as' => 'get-alumni-list',
            'uses' => 'Administration\InstitutionController@getAlumniList'
        ]);
    });
});
