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
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],
  'accounts_service' => [
    'create_invite' => env('CRAYDEL_ACCOUNTS_SERVICE').'/users/create-invite-request',
    'create_user' => env('CRAYDEL_ACCOUNTS_SERVICE').'/users/register'
  ],
  'schools_website' => env('CRAYDEL_SCHOOLS_WEBSITE'),
  'billing_service' => [
    'make_automated_payment' => env('CRAYDEL_BILLING_SERVICE').'/billing/rpc/create-an-automated-assessment-payment'
  ],
  'files_storage' => [
    'storage_url' => env('SPACE_URL'),
    'storage_key' => env('SPACE_KEY'),
    'storage_secret' => env('SPACE_SECRET'),
    'storage_bucket_name' => env('SPACE_BUCKET_NAME'),
    'storage_server_region' => env('SPACE_REGION'),
    'storage_server_file_cdn_path' => env('SPACE_FILE_CDN_URL')
  ],

];
