<?php

return [

    // Form field names
    'form' => [
        'login' => [
            'action' => '/login',
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
            'label' => 'Forgot password?',
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
        'description' => 'A cool site designed by me.',
        'domain' => env('APP_DOMAIN', 'example.com'),
    ],

];
