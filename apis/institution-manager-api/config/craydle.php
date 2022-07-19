<?php
return [
    'items_per_page' => env('ITEMS_PER_PAGE', 5),
    'system' => [
        'throttle' => [
            'default_throttle_limit' => env('DEFAULT_THROTTLE_LIMIT', 100000)
        ],
        'security' => [
            'maximum_uploaded_file_size' => 10
        ],
        'cache' => [
            'db_data_cache_length' => env('DB_DATA_CACHE_LENGTH', 10),
            'cache_for_life_unless_updated' => env('CACHE_FOR_LIFE_UNLESS_UPDATED')
        ]
    ],
    'logos' => [
        'minimum_width' => env('LOGO_MINIMUM_WIDTH', '600'),
        'minimum_height' => env('LOGO_MINIMUM_HEIGHT', '600'),
        'allowed_aspect_ration_minimum_multiplier' => env('ALLOWED_ASPECT_RATION_MINIMUM_MULTIPLIER', '1.3'),
        'allowed_aspect_ration_maximum_multiplier' => env('ALLOWED_ASPECT_RATION_MAXIMUM_MULTIPLIER', '2.5'),
        'image_contrast' => env('IMAGE_CONTRAST', 2),
        'images_brightness' => env('IMAGES_BRIGHTNESS', 0)
    ],
    'images' => [
        'institution_gallery' => [
            'institution_gallery_image_width_big' => env('INSTITUTION_GALLERY_IMAGE_WIDTH_BIG'),
            'institution_gallery_image_height_big' => env('INSTITUTION_GALLERY_IMAGE_HEIGHT_BIG'),
            'institution_gallery_image_width_small' => env('INSTITUTION_GALLERY_IMAGE_WIDTH_SMALL'),
            'institution_gallery_image_height_small' => env('INSTITUTION_GALLERY_IMAGE_HEIGHT_SMALL'),
        ]
    ],
    'files_storage' => [
        'storage_url' => env('SPACE_URL'),
        'storage_key' => env('SPACE_KEY'),
        'storage_secret' => env('SPACE_SECRET'),
        'storage_bucket_name' => env('SPACE_BUCKET_NAME'),
        'storage_server_region' => env('SPACE_REGION'),
        'storage_server_file_cdn_path' => env('SPACE_FILE_CDN_URL')
    ],
    'alumni_ratings_domain' =>env('APP_DOMAIN'),
    'max_data_chunck_size' =>100,
    'search_engine' => [
        'institution' => [
            'index_name' => 'craydel_institutions',
            'settings' => [
                'searchableAttributes' => [
                    'institution_type',
                    'institution_name',
                    'description',
                    'country',
                    'city',
                    'continent',
                    'ownership',
                    'is_featured',
                    'available_course_categories'
                ],
                'attributesForFaceting' => [
                    'searchable(institution_type)',
                    'searchable(country)',
                    'searchable(city)',
                    'searchable(ownership)',
                    'filterOnly(continent)',
                    'filterOnly(ownership)',
                    'filterOnly(is_featured)'
                ],
                'ranking' => [
                    'asc(continental_ranking)',
                    'typo',
                    'words',
                    'exact',
                    'filters',
                    'proximity',
                    'custom',
                    'attribute'
                ],
                'customRanking' => [
                    'asc(is_featured)',
                    'asc(continental_ranking)',
                    'asc(country_ranking)',
                    'asc(global_ranking)',
                    'asc(internal_system_ranking)',
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
                'minWordSizefor2Typos' => 4,
                'typoTolerance' => true,
                'allowTyposOnNumericTokens' => false,
                'queryLanguages' => ['en'],
                'ignorePlurals' => true,
                'replicas' => [
                    'virtual(sort_institutions_by_continental_ranking_asc)',
                    'virtual(sort_institutions_by_popularity_asc)',
                ]
            ],
            'replicas' => [
                [
                    'name' => 'sort_institutions_by_continental_ranking_asc',
                    'ranking' => [
                        'ASC(continental_ranking)',
                        'typo',
                        'geo',
                        'words',
                        'filters',
                        'proximity',
                        'attribute',
                        'exact',
                        'custom'
                    ]
                ],
                [
                    'name' => 'sort_institutions_by_popularity_asc',
                    'ranking' => [
                        'desc(system_internal_ranking)',
                        'typo',
                        'geo',
                        'words',
                        'filters',
                        'proximity',
                        'attribute',
                        'exact',
                        'custom'
                    ]
                ]
            ]
        ]
    ]
];
