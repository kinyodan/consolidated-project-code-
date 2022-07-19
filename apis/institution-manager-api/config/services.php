<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */
    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],
    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],
    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],
    'craydel_services' => [
        'notification' => [
            'base_url' => env('CRAYDEL_NOTIFICATION_SERVICE'),
            'endpoints' => [
                'send_email' => env('CRAYDEL_NOTIFICATION_SERVICE').'/notifications/send-email'
            ]
        ],
        'account_manager' => [
            'base_url' => env('CRAYDEL_ACCOUNT_MANAGER_SERVICE'),
            'endpoints' => [
                'verify_account' => env('CRAYDEL_ACCOUNT_MANAGER_SERVICE').'/users/verify-account/%s',
                'update_password' => env('CRAYDEL_ACCOUNT_MANAGER_SERVICE').'/users/set-new-password',
                'login' => env('CRAYDEL_ACCOUNT_MANAGER_SERVICE_API').'/users/login'
            ]
        ],
        'courses_manager' => [
            'base_url' => env('CRAYDEL_COURSES_MANAGER_SERVICE'),
            'endpoints' => [
                'get_institution_course_count' => env('CRAYDEL_COURSES_MANAGER_SERVICE').'/courses/rpc/course-count/%s',
                'get_institution_course_categories' => env('CRAYDEL_COURSES_MANAGER_SERVICE').'/courses/rpc/get-institution-course-categories/%s',
                'publish-courses-related-to-this-institution' => env('CRAYDEL_COURSES_MANAGER_SERVICE').'/courses/rpc/publish-courses-related-to-this-institution',
                'unpublish-courses-related-to-this-institution' => env('CRAYDEL_COURSES_MANAGER_SERVICE').'/courses/rpc/unpublish-courses-related-to-this-institution',
                'delete-courses-related-to-this-institution' => env('CRAYDEL_COURSES_MANAGER_SERVICE').'/courses/rpc/delete-courses-related-to-this-institution',

            ]
        ]
    ],
    'search_engine' => [
        'app_id' => env('SEARCH_ENGINE_APP_ID', 'CS1HG06GEE'),
        'api_key' => env('SEARCH_ENGINE_API_KEY', '2c43906ff0c8074c3faf112d95e30567')
    ]
];
