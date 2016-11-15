<?php

return [

    // Form field names
    'form' => [
        'login' => [
            'email' => [
                'field' => 'email',
                'label' => 'Email Address',
            ],
            'password' => [
                'field' => 'password',
                'label' => 'Password',
            ],
            'remember' => [
                'field' => 'remember',
            ],
            'submit' => [
                'label' => 'Login',
            ],
        ],
    ],

    // Page links
    'link' => [
        'forgot-password' => [
            'url' => '/password/reset',
            'lable' => 'Forgot password?',
        ],
        'privacy' => 'Privacy Policy',
        'terms' => 'Terms of Use',
    ],

    // Navigation info
    'nav' => [
        'dropdown-label' => 'My Account',
        'links' => [
            [
                '/user' => 'Edit Profile',
                '/user/password' => 'Change Password',
            ],
            [
                '/logout' => 'Logout',
            ],
        ],
    ],

    // Site info
    'site' => [
        'name' => 'My Site',
        'domain' => env('APP_DOMAIN', 'example.com'),
    ],

];
