<?php

use Zaimea\SDK\Groups\GroupsServiceProvider;

return [
    /*
    |--------------------------------------------------------------------------
    | Version
    |--------------------------------------------------------------------------
    */
    'version' => GroupsServiceProvider::VERSION,

    /*
    |--------------------------------------------------------------------------
    | API URL
    |--------------------------------------------------------------------------
    */
    'api_url' => env('ZAIMEA_API_URL', 'https://resources.click/api/v1/groups/'),

    /*
    |--------------------------------------------------------------------------
    | Authentication
    |--------------------------------------------------------------------------
    */
    'auth' => [
        // Token source: 'session' (default), 'cache', or 'custom'
        'token_source' => env('ZAIMEA_TOKEN_SOURCE', 'session'),
        
        // Session key where token is stored (for 'session' source)
        'session_key' => 'zaimea_access_token',
        
        // Cache configuration (for 'cache' source)
        'cache' => [
            'key_prefix' => 'zaimea_token_',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Security
    |--------------------------------------------------------------------------
    */
    'security' => [
        'force_https' => env('ZAIMEA_FORCE_HTTPS', true),
    ],

    /*
    |--------------------------------------------------------------------------
    | Logging
    |--------------------------------------------------------------------------
    */
    'logging' => env('ZAIMEA_LOGGING', false)
];