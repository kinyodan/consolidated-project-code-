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
        'invalid_authentication_token' => 'Invalid authentication token'
    ],
    'courses' => [
        'errors' => [
            'list_general_errors' => 'Error while listing the courses.',
            'error_saving_course' => 'There is an error saving the course details',
            'error_updating_course' => 'There is an error updating the course details',
            'missing_course_file' => 'Missing courses file while importing courses.',
            'invalid_course_file' => 'Invalid courses file while importing courses.',
            'error_uploading_course_file' => 'Error while uploading courses file.',
            'error_while_reading_courses_file' => 'Error while processing the courses file.',
            'invalid_course_file_type' => 'Invalid courses file while importing courses. Allowed types %s',
            'invalid_course_file_too_big' => 'The courses file is too big. Allowed size %s',
            'missing_country_code' => 'Missing country code.',
            'missing_institution_code' => 'Missing institution code',
            'missing_course_name' => 'Missing course name',
            'duplicate_course_name' => 'Duplicate course name',
            'invalid_description' => 'Invalid course description',
            'invalid_course_overview' => 'Invalid course overview',
            'invalid_discipline_code' => 'Invalid course discipline',
            'missing_course_type' => 'Missing course type',
            'missing_course_graduate_level' => 'Missing course graduate level',
            'missing_linked_course_categories' => 'Missing linked course categories.',
            'institution_course_code' => 'Invalid institution course code',
            'invalid_attendance_type' => 'Invalid course attendance type',
            'invalid_learning_mode' => 'Invalid course learning mode',
            'invalid_faculty_code' => 'Invalid faculty code',
            'invalid_institution_website_course_url' => 'Invalid institution website course URL',
            'invalid_institution_website_application_form_url' => 'Invalid institution website application form URL',
            'invalid_enrollment_details' => 'Invalid enrollment details',
            'invalid_standard_fee_billing_type' => 'Invalid standard fee billing type',
            'invalid_standard_fee_currency' => 'Invalid standard fee currency',
            'invalid_standard_fee_payable' => 'Invalid standard fee payable.Value should be between 1 to 1 billion point 99',
            'invalid_standard_fee_breakdown' => 'Invalid standard fee breakdown',
            'invalid_foreign_student_fee_billing_type' => 'Invalid foreign student fee billing type',
            'invalid_foreign_student_fee_payable' => 'Invalid foreign student fee payable',
            'invalid_foreign_student_fee_breakdown' => 'Invalid foreign student fee breakdown',
            'invalid_course_structure_breakdown' => 'Invalid course structure breakdown',
            'invalid_course_duration' => 'Invalid course duration',
            'invalid_course_duration_category' => 'Invalid course duration category',
            'invalid_maximum_scholarship_available' => 'Invalid maximum scholarship available',
            'invalid_accredited_by' => 'Invalid accredited by institution name',
            'invalid_accreditation_organization_url' => 'Invalid accreditation organization URL',
            'invalid_course_image_file_type' => 'Invalid course image file type. Allowed file type %s',
            'invalid_course_image_file_size_too_big' => 'Course image file size is too big. Allowed size %s MBs',
            'invalid_course_image_file_size_can_not_be_found' => 'Course image file size can not be found',
            'image_should_below_minimum_width' => 'The course image width should be above %s px',
            'image_should_below_minimum_height' => 'The course image height should be above %s px',
            'image_have_an_invalid_aspect_ration' => '%s image has an invalid aspect ration.',
            'invalid_accreditation_body_acronym' => 'Invalid accreditation body acronym',
            'invalid_course_code' => 'Invalid course code',
            'invalid_meta_keywords' => 'Invalid course meta keywords',
            'invalid_meta_description' => 'Invalid course meta description',
            'invalid_linked_blog_articles' => 'Invalid linked blog articles',
            'invalid_standard_first_year_fee_payable_usd' => 'Invalid standard first year fee payable USD',
            'invalid_foreign_student_first_year_fee_payable_usd' => 'Invalid foreign student first year fee payable USD',
            'import' => [
                'missing_file_headers_to_compare' => 'Missing file headers to compare.',
                'unable_to_read_file' => 'Unable to read the uploaded file.',
                'unable_to_read_the_first_sheet' => 'Unable to read the first sheet.',
                'empty_excel_sheet_uploaded' => 'Empty excel sheet uploaded.',
                'invalid_file_headers' => 'The uploaded the file does not have the valid headers.'
            ]
        ],
        'success' => [
            'listed' => 'Listed',
            'validated' => 'Validated',
            'created' => 'Course has been created',
            'updated' => 'Course has been updated',
            'built' => 'Built',
            'course_indexed' => 'Indexed',
            'imported_awaiting_processing' => 'File has been queued for processing. %s records have been validated and will be imported.',
            'details_shown' => 'Course details listed',
            'lead_submitted' => 'Lead submitted',
            'featured' => 'Course has been featured',
            'un_featured' => 'Course has been un-featured',
            'is_deleted' => 'Course is already deleted',
            'deleted' => 'Course has been deleted',
            'is_unpublished' => 'Course is already unpublished',
            'unpublished' => 'Course has been unpublished',
            'is_published' => 'Course is already published',
            'published' => 'Course has been published',
            'bulk_deleted' => 'Courses has been queued for deletions',
            'bulk_published' => 'Courses has been queued for publication',
            'bulk_unpublished' => 'Courses has been queued for bulk unpublished'
        ]
    ],
    'subjects' => [
        'errors' => [
            'list_general_errors' => 'Error while listing the subjects.',
            'missing_subject_name' => 'Missing subject name.',
            'duplicate_subject_name' => 'Duplicate subject name is not allowed.',
            'is_selected' => 'Error in subject selection.',
            'is_deleted' => 'Error in subject deletion.'
        ],
        'success' => [
            'listed' => 'Subjects Listed.',
            'created' => 'Subject has been created.',
            'is_selected' => 'Subject is selected for edit.',
            'is_updated' => 'Subject has been updated.',
            'is_deleted' => 'Subject has been deleted.'
        ]
    ],
    'educations' => [
        'errors' => [
            'list_general_errors' => 'Error while listing the education types.',
            'missing_subject_name' => 'Missing education type name.',
            'duplicate_subject_name' => 'Duplicate education  type name is not allowed.',
            'missing_country_id' => 'Please select the country .',
            'is_selected' => 'Error in education type name selection.',
            'is_deleted' => 'Error in education type name deletion.'
        ],
        'success' => [
            'listed' => 'Education types Listed.',
            'created' => 'Education type has been created.',
            'is_selected' => 'Education type is selected for edit.',
            'is_updated' => 'Education type has been updated.',
            'is_deleted' => 'Education type has been deleted.'
        ]
    ],
    'display_subjects' => [
        'errors' => [
            'list_general_errors' => 'Error while displaying subjects.',
            'missing_education_type_id' => 'Please Select Education Type.',
            'academic_disciplines_id' => 'Please Select Academic Disciplines.',
            'missing_country_id' => 'Please select the country .',
            'missing_academic_disciplines_id' => 'Error in education type name selection.',
            'missing_subject_title' => 'Please enter subject description.',
            'missing_subject_title_description' => 'Please enter subject description',
            'missing_display_order' => 'Please enter display order.',

        ],
        'success' => [
            'listed' => 'Displaying subjects types Listed.',
            'created' => 'Displaying subjects has been created.',
            'is_selected' => 'Displaying subjects is selected for edit.',
            'is_updated' => 'Displaying subjects has been updated.',
            'is_deleted' => 'Displaying subjects has been deleted.'
        ]
    ],
    'grades' => [
        'errors' => [
            'list_general_errors' => 'Error while listing the grades .',
            'created' => 'Error while creating the grade.',
            'missing_min_value' => 'Please input the minimum grade value.',
            'missing_max_value' => 'Please input the maximum grade value.',
            'missing_country_id' => 'Please select the country dropdown.',
            'missing_grade_equivalent' => 'Please input the grade equivalent.',
            'is_selected' => 'Error in grade name selection.',
            'is_deleted' => 'Error in grade name deletion.'
        ],
        'success' => [
            'built' => 'Built',
            'listed' => 'Grades Listed.',
            'created' => 'Grade has been created.',
            'is_selected' => 'Grade is selected for edit.',
            'is_updated' => 'Grade has been updated.',
            'is_deleted' => 'Grades has been deleted.',
            'select_country' => 'Grades from country selected.'
        ]
    ],
    'clusters' => [
        'errors' => [
            'list_general_errors' => 'Error while listing the cluster.',
            'missing_cluster_name' => 'Missing cluster name.',
            'duplicate_cluster_name' => 'Duplicate cluster name is not allowed.',
            'is_selected' => 'Error in cluster type name selection.',
            'is_deleted' => 'Error in cluster type name deletion.',
            'missing_cluster_id' => 'Missing cluster ID.',
            'missing_subject_id' => 'Missing subject ID.',
            'missing_education_type_id' => 'Missing education type ID.',
            'missing_country_id' => 'Missing Country ID.',
            'missing_is_primary' => 'Missing Primary ID.',
            'missing_is_career_pathway_id' => 'Missing Career Pathway ID.',
            'missing_is_course_code' => 'Missing Course Code.',
            'cant_deleted' => 'a foreign key constraint fails'
        ],
        'success' => [
            'built' => 'Built',
            'listed' => 'Clusters  Listed.',
            'created' => 'Cluster has been created.',
            'select' => 'Cluster Subjects are selected.',
            'is_selected' => 'Cluster is selected for edit.',
            'is_updated' => 'Cluster has been updated.',
            'is_deleted' => 'Cluster has been deleted.'
        ]
    ],
    'pathways' => [
        'errors' => [
            'list_general_errors' => 'Error while listing the pathways.',
            'missing_career_pathways_id' => 'Missing careers pathways id.',
            'missing_academic_disciplines_id' => 'Missing academic disciplines id.',
            'missing_career_pathways_name' => 'Missing career pathway name'

        ],
        'success' => [
            'built' => 'Built',
            'listed' => 'Pathways  Listed.',
            'created' => 'Pathways has been created.',
            'select' => 'Pathways are selected.',
            'is_selected' => 'Pathways is selected for edit.',
            'is_updated' => 'Pathways has been updated.',
            'is_deleted' => 'Pathways has been deleted.',
        ]
    ],
    'course_application' => [
        'workflow' => [
            'notifications' => [
                'sender_name_format' => '%s at Craydel',
                'new_lead' => 'Hi %s 🚀 Welcome to Craydel!🚀',
                'course_selected_email_subject' => 'Hi%s! 👏 Well done on starting your journey with Craydel!👏',
                'course_application_submitted_subject' => 'Congratulations %s 🎉 Your Application on Craydel is Complete! 🎉',
                'course_application_offer_received' => "CONGRATULATIONS %s! 🎊 We've got your University offer letter! 🎊",
                'course_application_student_enrolled_subject' => '%s! 🚀 You are officially enrolled at %s! 🚀'
            ]
        ]
    ]
];
