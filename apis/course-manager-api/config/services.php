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
    'admissions_team_default_number' => '0783125125',
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
        'send_email' => env('CRAYDEL_NOTIFICATION_SERVICE') . '/notifications/send-email'
      ]
    ],
    'account_manager' => [
      'base_url' => env('CRAYDEL_ACCOUNT_MANAGER_SERVICE'),
      'endpoints' => [
        'verify_account' => env('CRAYDEL_ACCOUNT_MANAGER_SERVICE') . '/users/verify-account/%s',
        'update_password' => env('CRAYDEL_ACCOUNT_MANAGER_SERVICE') . '/users/set-new-password',
        'login' => env('CRAYDEL_ACCOUNT_MANAGER_SERVICE_API') . '/users/login'
      ]
    ],
    'institutions_manager' => [
      'base_url' => env('CRAYDEL_INSTITUTIONS_MANAGER_SERVICE'),
      'endpoints' => [
        'get_institution_summary' => env('CRAYDEL_INSTITUTIONS_MANAGER_SERVICE') . '/institutions/rpc/get-summary/%s',
        'get_institutions_list' => env('CRAYDEL_INSTITUTIONS_MANAGER_SERVICE') . '/institutions/rpc/institutions-list',
        'get_currencies_list' => env('CRAYDEL_INSTITUTIONS_MANAGER_SERVICE') . '/institutions/rpc/currencies',
        'get_countries_list' => env('CRAYDEL_INSTITUTIONS_MANAGER_SERVICE') . '/institutions/rpc/countries',
      ]
    ],
    'career_pathways_service' => [
      'career_pathways_service_url' => env('CRAYDEL_CAREER_PATHWAYS_SERVICE') . '/pathways/admin'
    ],
    'school_service' => [
      'higher_learning_update_notification' => env('CRAYDEL_SCHOOL_SERVICE') . '/receive-user-higher-learning-update-notification'
    ],
    'data_lake' => [
      'push_updates' => env('CRAYDEL_INSTITUTIONS_DATA_LAKE') . '/api/events/listen'
    ],
    'key_phrases_extractor' => [
      'key_phrases_extractor_url' => env('CRAYDEL_KEY_PHRASES_EXTRACTOR_SERVICE')
    ],
    'search_engine' => [
      'search_engine_url' => env('CRAYDEL_SEARCH_ENGINE_SERVICE'),
      'search_engine_indexing_url' => env('CRAYDEL_SEARCH_ENGINE_INDEXING_SERVICE')
    ]
  ],
    'zoho_crm' => [
    'api_key' => env('ZOHO_CRM_API_KEY'),
    'access_token' => env('ZOHO_ACCESS_TOKEN'),
    'refresh_token' => env('ZOHO_CRM_REFRESH_TOKEN'),
    'zoho_crm_api_client_id' => env('ZOHO_CRM_API_CLIENT_ID'),
    'zoho_crm_api_client_secret' => env('ZOHO_CRM_API_CLIENT_SECRET'),
    'zoho_crm_authentication_url' => env('ZOHO_CRM_AUTHENTICATION_URL'),
    'zoho_crm_refresh_token_url' => env('ZOHO_CRM_REFRESH_TOKEN_URL'),
    'zoho_redirect_url' => env('ZOHO_REDIRECT_URL'),
    'zoho_crm_default_lead_assignment_rule' => env('ZOHO_CRM_DEFAULT_LEAD_ASSIGNMENT_RULE'),
    'leads' => [
      'api_url' => env('ZOHO_CRM_BASE_URL', 'https://www.zohoapis.com') . '/crm/v2.1/Leads',
      'force_push_leads' => env('ZOHO_CRM_BASE_URL', 'https://www.zohoapis.com') . '/crm/v2.1/Leads/upsert',
      'get_lead_details' => env('ZOHO_CRM_BASE_URL', 'https://www.zohoapis.com') . '/crm/v2.1/Leads/%s',
      'delete_api_url' => env('ZOHO_CRM_BASE_URL', 'https://www.zohoapis.com') . '/crm/v2.1/Leads?ids=%s&wf_trigger=true',
      'single_delete_api_url' => env('ZOHO_CRM_BASE_URL', 'https://www.zohoapis.com') . '/crm/v2.1/Leads/%s',
      'update_lead_details' => env('ZOHO_CRM_BASE_URL', 'https://www.zohoapis.com') . '/crm/v2.1/Leads/%s',
    ],
    'users' => [
      'get_user_details' => env('ZOHO_CRM_BASE_URL', 'https://www.zohoapis.com') . '/crm/v2.1/users/%s'
    ],
    'opportunity' => [
      'get_details' => env('ZOHO_CRM_BASE_URL', 'https://www.zohoapis.com') . '/crm/v2.1/Deals/%s',
      'update_opportunity_details' => env('ZOHO_CRM_BASE_URL', 'https://www.zohoapis.com') . '/crm/v2.1/Deals/%s',
      'get_activities' => env('ZOHO_CRM_BASE_URL', 'https://www.zohoapis.com') . '/crm/v2.1/Deals/%s/Stage_History'
    ],
    'customers' => [
      'get_details' => env('ZOHO_CRM_BASE_URL', 'https://www.zohoapis.com') . '/crm/v2.1/Contacts/%s',
    ]
  ],
    'fixer_forex' => [
    'api_key' => env('FIXER_FOREX_API_KEY'),
    'api_url' => env('FIXER_FOREX_API_URL'),
    'endpoints' => [
      'convert_single_value' => env('FIXER_FOREX_API_URL') . '/convert?access_key=%s&from=%s&to=%s&amount=%d',
      'latest_exchange_rate_again_usd' => env('FIXER_FOREX_API_URL') . '/latest?access_key=%s&base=USD'
    ]
  ],
    'mailjet' => [
        'api_key' => env('MAIL_JET_API_KEY', 'e33b971691def0f4a65379c3efe791f7'),
        'api_secret' => env('MAIL_JET_API_SECRET', '51cba5cc680148042977d0ce419a6cd5'),
        'contact_list_id' => env('MAIL_JET_CONTACT_LIST_ID', '19958'),
        'contact_list_address' => env('MAIL_JET_CONTACT_LIST_ADDRESS', 'tfhxe3np1'),
        'api_url' => [
            'add_contact' => 'https://api.mailjet.com/v3/REST/contact',
            'subscribe_contact_to_list' => 'https://api.mailjet.com/v3/REST/listrecipient'
        ]
    ]
];
