<?php
/**
 * Created by PhpStorm.
 * User: 001908
 * Date: 27/03/2019
 * Time: 17:16
 */
return [
    'authorization' => [
        'too_many_api_requests' => 'Too many API requests. Please try again after a few minutes',
        'request_not_allowed' => 'You not not allowed to perform the requested task.',
        'missing_user_permission' => 'Invalid user permission provided',
        'missing_service_code_in_permission_lookup' => 'Missing service code in permission lookup',
    ],
    'authentication' => [
        'missing_auth_token' => 'Missing authentication token.',
        'expired_authentication_token' => 'Expired authentication token',
        'invalid_authentication_token' => 'Invalid authentication token',
        'not_logged_in' => 'Not logged in.'
    ],
    'institutions' => [
        'errors' => [
            'list_general_errors' => 'Error while listing institutions.',
            'error_creating_institution' => 'Error while creating the institution.',
            'missing_institution_name' => 'Missing institution name.',
            'duplicate_institution_name' => 'Duplicate institution name not allowed.',
            'missing_country_code' => 'Missing country code.',
            'invalid_description' => 'Invalid institution description.',
            'invalid_profile_details' => 'Invalid profile details.',
            'invalid_logo_file_type' => 'Invalid logo file type. Allowed %s',
            'invalid_institution_list_file_type' => 'Invalid institution list file type. Allowed %s',
            'institution_logo_file_size_too_big' => 'The institution logo size is bigger than %s',
            'institution_list_file_size_too_big' => 'The institution list file size is bigger than %s',
            'unable_to_get_the_logo_size' => 'Error processing the uploaded logo',
            'unable_to_get_the_institution_list_file_size' => 'Error processing the uploaded list',
            'logo_should_below_minimum_width' => 'The logo width should be above %s px',
            'logo_should_below_minimum_height' => 'The logo height should be above %s px',
            'logo_have_an_invalid_aspect_ration' => 'Logo has an invalid aspect ration.',
            'invalid_institution_code' => 'Invalid institution code.',
            'missing_institution_code' => 'Missing institution code.',
            'missing_institution_type' => 'Missing institution type.',
            'missing_institution_ownership_type' => 'Missing institution ownership type.',
            'missing_user_email' => 'Missing user email. Please ensure you are logged in!',
            'missing_institution_list_file' => 'Missing institution list file.',
            'error_uploading_institution_list' => 'Error while uploading the institution list file.',
            'published' => 'Error in publishing the institution',
            'unpublished' => 'Error in unpublishing the institution',
            'review' => [
                'invalid_missing_rating_score' => 'Missing or invalid rating score.',
                'invalid_missing_full_names' => 'Missing or invalid full names.',
                'invalid_course_taken' => 'Missing course taken.',
                'invalid_graduation_year' => 'Missing graduation year.',
                'invalid_reviews' => 'Missing review.',
                'error_while_submitting_review' => 'Error while submitting the review.'
            ],
            'gallery' => [
                'missing_asset_name' => 'Missing asset name',
                'duplicate_asset_name' => 'Other asset exists with the same name.',
                'invalid_asset_position' => 'Invalid asset display position.',
                'invalid_asset_caption' => 'Invalid asset caption/description.',
                'invalid_is_featured_value' => 'Invalid asset is featured value.',
                'missing_asset_type' => 'Missing asset type',
                'invalid_asset_type' => 'Invalid asset types. (allowed types %s)',
                'asset_image_not_uploaded' => 'Asset image not uploaded.',
                'missing_asset_url' => 'Missing asset video link.',
                'invalid_asset_url' => 'Invalid asset video link.',
                'unsupported_file_type' => 'Invalid gallery image file type is not allowed. Allowed %s',
                'institution_logo_file_size_too_big' => 'The institution logo size is bigger than %s',
                'institution_list_file_size_too_big' => 'The institution list file size is bigger than %s',
                'unable_to_get_the_image_size' => 'Unable to retrieve the image file size.',
                'image_should_below_minimum_width' => 'The image width should be above %s px',
                'image_should_below_minimum_height' => 'The image height should be above %s px',
                'image_has_an_invalid_aspect_ration' => 'Logo has an invalid aspect ration.',
                'error_saving_gallery_asset' => 'Error while saving the gallery asset.',
                'missing_asset_code' => 'Missing asset code',
                'invalid_asset_code' => 'Invalid asset code',
                'error_deleting_gallery_asset' => 'Error while deleting the gallery asset'
            ],
            'accreditation' => [
                'invalid_organization_name' => 'Invalid organization name',
                'duplicate_accreditation_name' => 'Duplicate accreditation name not allowed',
                'invalid_accreditation_description' => 'Invalid accreditation description',
                'error_saving_institution_accreditation' => 'Error saving the institution accreditation',
                'error_updating_institution_accreditation' => 'Error updating the institution accreditation',
                'missing_accreditation_id' => 'Missing accreditation ID.',
                'invalid_accreditation_id' => 'Invalid accreditation ID.',
                'error_deleting_accreditation' => 'Error deleting the accreditation.'
            ],
            'alumni' => [
                'invalid_alumnus_name' => 'Invalid alumnus name.',
                'duplicate_alumnus_name' => 'Duplicate alumnus name is not allowed.',
                'invalid_graduation_year' => 'Invalid graduation year',
                'invalid_course_taken' => 'Invalid course taken.',
                'invalid_current_employer' => 'Invalid current employer name',
                'invalid_current_employment_position' => 'Invalid current employment position',
                'invalid_personal_profile_url' => 'Invalid personal profile URL',
                'error_saving_alumnus' => 'Error saving the alumnus details',
                'invalid_alumnus_id' => 'Invalid alumnus ID',
                'missing_alumnus_id' => 'Missing alumnus ID',
                'error_updating_alumnus' => 'Error while updating the alumnus details',
                'error_deleting_alumnus' => 'Error while deleting the alumnus.'
            ],
            'highlight' => [
                'missing_highlight_id' => 'Missing highlight ID',
                'invalid_highlight_id' => 'Invalid highlight ID',
                'invalidate_highlight_validation_mode' => 'Invalid institution highlight mode.',
                'invalid_key_highlight' => 'Invalid institution key highlight',
                'invalid_key_highlight_description' => 'Invalid institution highlight description',
                'invalid_display_order' => 'Invalid institution highlight display order',
                'duplicate_key_highlight' => 'Duplicate key highlight name not allowed.',
                'error_while_saving_highlight' => 'Error while saving the institution highlight',
                'error_while_updating_highlight' => 'Error while updating the institution highlight',
                'missing_key_highlight_id' => 'Missing institution key highlight ID',
                'invalid_key_highlight_id' => 'Invalid institution key highlight ID',
                'error_while_deleting_the_highlight' => 'Error while deleting the institution highlight.'
            ]
        ],
        'success' => [
            'listed' => 'Listed',
            'validated' => 'Validated',
            'created' => 'Institution has been created',
            'uploaded' => 'The institution List has been uploaded. Please allow some time for processing.',
            'updated' => 'Institution has been updated',
            'built' => 'Built',
            'indexed' => 'Indexed',
            'details_shown' => 'Show details',
            'review_submitted' => 'Review submitted',
            'featured' => 'Institution has been featured',
            'un_featured' => 'Institution has been un-featured',
            'gallery_item_created' => 'Gallery item has been created.',
            'gallery_item_updated' => 'Gallery item has been updated.',
            'gallery_item_deleted' => 'Gallery item has been deleted.',
            'gallery_listed' => 'Gallery has been listed.',
            'gallery_item_featured' => 'Gallery has been featured.',
            'accreditation_saved' => 'The accreditation has been saved',
            'accreditation_updated' => 'The accreditation has been updated',
            'accreditation_listed' => 'Accreditation listed',
            'accreditation_deleted' => 'The accreditation has been deleted.',
            'accreditation_shown' => 'Accreditation shown',
            'alumnus_saved' => 'The institution alumnus has been saved',
            'alumni_listed' => 'Alumni listed',
            'alumni_shown' => 'Alumni details shown',
            'alumnus_update' => 'The alumnus has been updated',
            'alumnus_deleted' => 'The alumnus has been deleted',
            'alumnus_built' => 'The new alumnus request has been built',
            'highlight_shown' => 'The highlight has been shown.',
            'highlight_saved' => 'Institution highlight has been saved.',
            'highlight_updated' => 'Institution highlight has been updated.',
            'highlight_deleted' => 'Institution highlight has been deleted.',
            'highlights_listed' => 'Institution highlights have been listed',
            'published' => 'Institution has been published',
            'is_published' => 'Institution is already published',
            'unpublished' => 'Institution has been unpublished',
            'is_unpublished' => 'Institution is already unpublished',
            'alumni_questions_listed' => 'Alumni questions listed'
        ]
    ],
    'general' => [
        'errors' => [
            'invalid_logo_file_type' => 'Invalid image file type',
            'unsupported_file_type' => 'Unsupported file type',
            'unable_to_get_the_image_size' => 'Unable to get the image size',
            'image_should_below_minimum_width' => 'Image should be below minimum width',
            'image_has_an_invalid_aspect_ration' => 'Image has an invalid aspect ration',
            'invalid_image_parameter_field' => 'Invalid image parameter field'
        ]
    ],
    'alumni' => [
        'errors' => [
            'list_general_errors' => 'Error while listing alumni.',
            'error_creating_institution' => 'Error while creating the alumni.',
            'missing_question_title' => 'Missing question category title.',
            'duplicate_missing_question_title' => 'Duplicate question category title not allowed.',
            'duplicate_missing_question_description' => 'Duplicate question description not allowed.',
            'missing_question_category_id' => 'Please select question category',
            'missing_description' => 'Missing question description.',
            'missing_institution_alumni_id' =>'missing institution alumni_id',
            'missing_reviews' => 'Please enter the reviews.',
            'missing_order' => 'Please Select the display order.',
            'missing_country_code' => 'Missing country code.',
            'missing_ratings' => 'Please fill your ratings scores',
            'invalid_description' => 'Invalid institution description.',
            'invalid_profile_details' => 'Invalid profile details.',
            'invalid_logo_file_type' => 'Invalid logo file type. Allowed %s',
            'invalid_institution_list_file_type' => 'Invalid institution list file type. Allowed %s',
            'institution_logo_file_size_too_big' => 'The institution logo size is bigger than %s',
            'institution_list_file_size_too_big' => 'The institution list file size is bigger than %s',
            'unable_to_get_the_logo_size' => 'Error processing the uploaded logo',
            'unable_to_get_the_institution_list_file_size' => 'Error processing the uploaded list',
            'logo_should_below_minimum_width' => 'The logo width should be above %s px',
            'logo_should_below_minimum_height' => 'The logo height should be above %s px',
            'logo_have_an_invalid_aspect_ration' => 'Logo has an invalid aspect ration.',
            'invalid_institution_code' => 'Invalid institution code.',
            'missing_institution_code' => 'Missing institution code.',
            'missing_institution_type' => 'Missing institution type.',
            'missing_institution_ownership_type' => 'Missing institution ownership type.',
            'missing_user_email' => 'Missing user email. Please ensure you are logged in!',
            'missing_institution_list_file' => 'Missing alumni list file.',
            'error_uploading_institution_list' => 'Error while uploading the alumni list file.',
            'error_creating_profile' =>'Error in updating profile',
            'error_creating_response' => 'Error is responding to questions',
            'error_creating_reviews' => 'Errors in creating reviews',
            'missing_show_your_profile' =>'Please check to show my Linkedin profile picture radio button',
            'missing_is_consented' => 'Please check to give my consent to Craydel radio button',
            'missing_alumni_name' => 'Missing alumni name',
            'missing_email' => 'Missing alumni email',
            'missing_university_name' => 'Missing university name',
            'missing_course_type' => 'Missing course type',
            'missing_course_category' => 'Missing course category',
            'missing_current_organisation' => 'Missing current organisation',
            'missing_current_position' => 'Missing current position',
            'missing_current_location' => 'Missing current location',
            'missing_graduation_year' => 'Missing graduation year'

        ],
        'success' => [
            'listed' => 'Listed',
            'validated' => 'Validated',
            'select' => 'Select Questions',
            'created' => 'Alumni has been created',
            'category' => 'Category has been created',
            'questions' => 'Question has been created',
            'reviews' => 'Reviews has been created',
            'response' => 'Response has been added',
            'responded' => 'Response already added',
            'uploaded' => 'The Alumni List has been uploaded. Please allow some time for processing.',
            'updated' => 'Alumni has been updated',
            'profile' => ' Profile has been updated'

        ]
    ],
];
