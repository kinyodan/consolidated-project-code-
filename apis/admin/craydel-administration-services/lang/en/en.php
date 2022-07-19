<?php
return [
  
  'licenses' => [
    
    'errors' => [
      'allowed_license_count_missing' => 'Allowed license count cannot be null',
    
    
    ],
    'success' => [
      'build' => 'build',
      'listed' => 'Listed',
      'validated' => 'Validated',
      'added' => 'Allowed license has been added',
    
    ]
  ],
  
  'students' => [
    'errors' => [
      'missing_name' => 'Missing student name.',
      'missing_curriculum_correct_id' => 'Please select valid curriculum id.',
      'class_name_exists' => 'Class Details exists'
    
    
    ],
    'success' => [
      'build' => 'build',
      'listed' => 'Listed',
      'validated' => 'Validated',
      'added' => 'Student has been created',
      'updated' => 'Student has been updated',
      'deleted' => 'Student has been deleted',
    
    ]
  ],
  'classes' => [
    'errors' => [
      'missing_name' => 'Missing class name.',
      'missing_curriculum_correct_id' => 'Please select valid curriculum id.',
      'class_name_exists' => 'Class Details exists'
    
    
    ],
    'success' => [
      'build' => 'build',
      'listed' => 'Listed',
      'validated' => 'Validated',
      'added' => 'Class has been created',
      'updated' => 'Class has been updated',
      'deleted' => 'Class has been deleted',
    
    ]
  ],
  'admins' => [
    'errors' => [
      'missing_name' => 'Missing admin name.',
      'missing_email' => 'Missing admin email.',
      'incorrect_email' => 'Please insert the correct email',
      'missing_phone' => 'Missing admin phone',
      'missing_address' => 'Missing admin address',
      'admin_role' => 'Missing admin role',
      'admin_details_exists' => 'Admin Details exists'
    
    
    ],
    'success' => [
      'build' => 'build',
      'listed' => 'Listed',
      'validated' => 'Validated',
      'added' => 'Admin has been created',
      'updated' => 'Admin has been updated',
      'deleted' => 'Admin has been deleted',
    
    ]
  ],
  'streams' => [
    'errors' => [
      'missing_name' => 'Missing stream name.',
      'stream_name_exists' => 'Stream name exists',
    
    
    ],
    'success' => [
      'build' => 'build',
      'listed' => 'Listed',
      'validated' => 'Validated',
      'added' => 'Stream has been created',
      'updated' => 'Stream has been updated',
      'deleted' => 'Stream has been deleted',
    
    ]
  ],
  'schools' => [
    'errors' => [
      'missing_name' => 'Missing school name.',
      'missing_school_email' => 'Missing school email',
      'incorrect_email' => 'Incorrect email',
      'school_phone' => 'Missing School phone',
      'school_address' => 'Missing School Address',
      'discount_value' => 'Discount value can not be null',
      'name_exists' => 'School name exists',
      'email_exists' => 'School email exists',
      'phone_number_exists' => 'School Phone exists',
    
    ],
    'success' => [
      'build' => 'build',
      'listed' => 'Listed',
      'validated' => 'Validated',
      'added' => 'School has been created',
      'updated' => 'School has been updated',
      'deleted' => 'School has been deleted',
    ]
  ],
  'curriculum' => [
    'errors' => [
      'missing_name' => 'Missing curriculum name.',
      'country_code_exists' => 'Incorrect country_code',
      'missing_country_code' => 'Missing country_code',
      'name_exists' => 'curriculum name exists',
      'curriculum_code' => 'Missing curriculum code',
      'exist_curriculum_code' => 'curriculum code exist'
    
    ],
    'success' => [
      'listed' => 'Listed',
      'validated' => 'Validated',
      'added' => 'curriculum has been created',
      'updated' => 'curriculum has been updated',
      'deleted' => 'curriculum has been deleted',
    
    ]
  ],
  'years' => [
    'errors' => [
      'missing_name' => 'Missing year.',
      'missing_description' => 'Missing description',
      'name_exists' => 'Year exists'
    
    ],
    'success' => [
      'listed' => 'Listed',
      'validated' => 'Validated',
      'added' => 'Year has been created',
      'updated' => 'Year has been updated',
      'deleted' => 'Year has been deleted',
    
    ]
  ],
  'bank_details' => [
    'errors' => [
      'missing_account_name' => 'Missing account name.',
      'missing_account_number' => 'Missing account number',
      'missing_bank_name' => 'Missing bank name',
      'missing_branch_name' => 'Missing branch name',
      'missing_swift_code' => 'Missing swift code',
      'bank_details_name_exists' => 'School Bank details exists'
    
    ],
    'success' => [
      'listed' => 'Listed',
      'validated' => 'Validated',
      'added' => 'School Bank Details has been created',
      'updated' => 'School Bank Details has been updated',
      'deleted' => 'School Bank Details has been deleted',
    
    ]
  ],
];

