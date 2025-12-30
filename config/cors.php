<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure your settings for cross-origin resource sharing
    | or "CORS". This determines what cross-origin operations may execute
    | in web browsers. You are free to adjust these settings as needed.
    |
    */

    'paths' => ['api/*', 'sanctum/csrf-cookie', 'doc-dms/*'],

    'allowed_methods' => ['*'], // Allow all methods (GET, POST, PUT, DELETE, etc.)

    'allowed_origins' => [
        'https://dev.docdms.com',
        'https://docdms.com',
    ],

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'], // Allow all headers

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => true, // If you use cookies or auth headers
];
