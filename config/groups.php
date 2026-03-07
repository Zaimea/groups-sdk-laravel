<?php

use Zaimea\SDK\Groups\GroupsServiceProvider;

return [
    /*
    |--------------------------------------------------------------------------
    | Credentials
    |--------------------------------------------------------------------------
    */
    'credentials' => [
        'key'    => env('ZAIMEA_CLIENT_ID', ''),
        'secret' => env('ZAIMEA_CLIENT_SECRET', ''),
    ],
    
    /*
    |--------------------------------------------------------------------------
    | Version
    |--------------------------------------------------------------------------
    */
    'version' => GroupsServiceProvider::VERSION,

    /*
    |--------------------------------------------------------------------------
    | API Configuration
    |--------------------------------------------------------------------------
    */
    'api' => [
        'base_url' => env('ZAIMEA_API_URL', 'https://resources.click/api/v1'),
        'timeout' => env('ZAIMEA_API_TIMEOUT', 30),
        'retries' => env('ZAIMEA_API_RETRIES', 3),
    ],

    /*
    |--------------------------------------------------------------------------
    | Authentication
    |--------------------------------------------------------------------------
    */
    'auth' => [
        // Token source: 'session' (default), 'cache', or 'custom'
        'token_source' => env('ZAIMEA_TOKEN_SOURCE', 'session'),
        
        // Session key where token is stored (for 'session' source)
        'session_key' => 'access_token',
        
        // Cache configuration (for 'cache' source)
        'cache' => [
            'store' => env('ZAIMEA_CACHE_STORE', 'default'),
            'key_prefix' => 'zaimea_token_',
            'ttl' => 3600, // seconds
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Security
    |--------------------------------------------------------------------------
    */
    'security' => [
        'force_https' => env('ZAIMEA_FORCE_HTTPS', true),
        'verify_ssl' => env('ZAIMEA_VERIFY_SSL', true),
    ],

    /*
    |--------------------------------------------------------------------------
    | Features
    |--------------------------------------------------------------------------
    */
    'features' => [
        'auto_retry' => env('ZAIMEA_AUTO_RETRY', true),
        'logging' => env('ZAIMEA_LOGGING', false),
    ],
];