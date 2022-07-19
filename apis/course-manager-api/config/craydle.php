<?php

use App\Http\Controllers\Providers\LeadManagement\LeadProviders;

return [
  'search_engine' => [
    'app_id' => env('SEARCH_ENGINE_APP_ID'),
    'api_key' => env('SEARCH_ENGINE_API_KEY'),
    'courses' => [
      'index_name' => 'craydel_courses',
      'settings' => [
        'searchableAttributes' => [
          'course_name',
          'institution_name',
          'description',
          'country',
          'course_overview',
          'discipline',
          'course_type',
          'graduate_level',
          'attendance_type',
          'learning_mode',
          'course_requirements',
          'enrollment_details',
          'accredited_by',
          'institution_code',
          'is_featured',
          'standard_fee_payable_usd'
        ],
        'attributesForFaceting' => [
          'searchable(country)',
          'searchable(course_type)',
          'searchable(graduate_level)',
          'searchable(discipline)',
          'searchable(standard_fee_payable_usd)',
          'searchable(learning_mode)',
          'searchable(attendance_type)',
          'filterOnly(institution_ranking)',
          'filterOnly(institution_code)',
          'filterOnly(course_name)',
          'filterOnly(course_rating)',
          'filterOnly(course_duration)',
          'filterOnly(enrollment_details)',
          'filterOnly(is_featured)',
        ],
        'ranking' => [
          'exact',
          'attribute',
          'words',
          'typo',
          'filters',
          'custom',
          'proximity',
          'asc(popularity)',
        ],
        'customRanking' => [
          'asc(is_featured)',
          'asc(popularity)',
          'asc(standard_fee_payable_usd)',
          'asc(internal_course_ranking)',
          'asc(institution_ranking)',
          'asc(course_rating)'
        ],
        'sortFacetValuesBy' => 'count',
        'maxValuesPerFacet' => 10,
        'attributesToHighlight' => [
          'course_name',
          'description'
        ],
        'attributesToSnippet' => [
          'description:80'
        ],
        'highlightPreTag' => '<strong>',
        'highlightPostTag' => '</strong>',
        'snippetEllipsisText' => '…',
        'hitsPerPage' => 10,
        'paginationLimitedTo' => 10000,
        'minWordSizefor1Typo' => 4,
        'minWordSizefor2Typos' => 8,
        'typoTolerance' => 'strict',
        'allowTyposOnNumericTokens' => false,
        'queryLanguages' => ['en'],
        'ignorePlurals' => true,
        'replicas' => [
          'virtual(sort_courses_by_popularity_asc)',
          'virtual(sort_courses_by_scholarship_available_desc)',
          'virtual(sort_courses_by_fee_payable_asc)',
          'virtual(sort_courses_by_fee_payable_desc)',
        ]
      ],
      'replicas' => [
        'popular_courses'
      ]
    ]
  ],
  'keyword_extractor_api' => env('CRAYDEL_KEYPHRASES_EXTRACTOR_SERVICE'),
  'swoole_search_api' => env('CRAYDEL_SEARCH_ENGINE_SERVICE'),
  'default_forex_provider' => env('DEFAULT_FOREX_PROVIDER'),
  'default_lms_provider' => env('DEFAULT_LMS_PROVIDER', LeadProviders::ZOHO_LEAD_PROVIDER),
  'items_per_page' => env('ITEMS_PER_PAGE', 5),
  'default_data_chunk_size' => env('DEFAULT_DATA_CHUNK_SIZE', 10),
  'course_upload_chunk_size' => env('COURSE_UPLOAD_CHUNK_SIZE', 10),
  'system' => [
    'throttle' => [
      'default_throttle_limit' => env('DEFAULT_THROTTLE_LIMIT', 100000)
    ],
    'security' => [
      'maximum_uploaded_file_size' => env('MAXIMUM_UPLOADED_FILE_SIZE', 10)
    ],
    'cache' => [
      'db_data_cache_length' => env('DB_DATA_CACHE_LENGTH', 10),
      'forex_api_cache_length' => env('FOREX_API_CACHE_LENGTH', 10),
      'long_lived_cache_length' => env('LONG_LIVED_CACHE_LENGTH', 10),
    ]
  ],
  'images' => [
    'minimum_width' => env('LOGO_MINIMUM_WIDTH', '600'),
    'minimum_height' => env('LOGO_MINIMUM_HEIGHT', '600'),
    'allowed_aspect_ration_minimum_multiplier' => env('ALLOWED_ASPECT_RATION_MINIMUM_MULTIPLIER', '1'),
    'allowed_aspect_ration_maximum_multiplier' => env('ALLOWED_ASPECT_RATION_MAXIMUM_MULTIPLIER', '4'),
    'image_contrast' => env('IMAGE_CONTRAST', 2),
    'images_brightness' => env('IMAGES_BRIGHTNESS', 0)
  ],
  'files_storage' => [
    'storage_url' => env('SPACE_URL'),
    'storage_key' => env('SPACE_KEY'),
    'storage_secret' => env('SPACE_SECRET'),
    'storage_bucket_name' => env('SPACE_BUCKET_NAME'),
    'storage_server_region' => env('SPACE_REGION'),
    'storage_server_file_cdn_path' => env('SPACE_FILE_CDN_URL')
  ],
  'lead_management' => [
    'default_lead_status' => env('DEFAULT_LEAD_STATUS', 'New'),
    'default_lead_source' => env('DEFAULT_LEAD_SOURCE', 'Craydel Marketplace'),
    'workflow' => [
      'new_lead' => [
        'subject' => 'Hi %s! 🚀 Welcome to Craydel! 🚀'
      ],
      'course_selected' => [
        'subject' => 'Hi %s! 👏 Well done on starting your journey with Craydel ! 👏'
      ],
      'application_done' => [
        'subject' => 'Congratulations %s! 🤗 Your Application on Craydel is Complete! 🤗'
      ],
      'offer_letter_received' => [
        'subject' => 'CONGRATULATIONS %s 🎊 We have got your University offer letter! 🎊'
      ],
      'enrolment_done' => [
        'subject' => '%s! 🚀 You are officially enrolled at %s! 🚀'
      ]
    ]
  ]
];
