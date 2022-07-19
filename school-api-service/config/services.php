<?php
return [
    'accounts_service' => [
        'create_invite' => env('CRAYDEL_ACCOUNTS_SERVICE').'/users/create-invite-request',
        'create_user' => env('CRAYDEL_ACCOUNTS_SERVICE').'/users/register'
    ],
    'schools_website' => env('CRAYDEL_SCHOOLS_WEBSITE'),
    'billing_service' => [
        'make_automated_payment' => env('CRAYDEL_BILLING_SERVICE').'/billing/rpc/create-an-automated-assessment-payment'
    ]
];
