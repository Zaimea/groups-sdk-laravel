---
title: Installation
description: How to install and configure the Zaimea Groups SDK for Laravel
github: https://github.com/zaimea/groups-sdk-laravel/edit/main/docs/
onThisArticle: true
sidebar: true
rightbar: true
---

# Installation

[[TOC]]

## Introduction

`zaimea/groups-sdk-laravel` is a powerful PHP SDK for interacting with the **Zaimea Groups API**. It provides a fluent, Laravel-friendly interface for managing groups, members, clients, projects, records, holidays, vacations, medicals, and much more.

### Features

- **Laravel 12+** integration with Service Provider and Facade
- **PHP 8.3+** with strict typing support
- Built on **GuzzleHTTP 7.8+**
- Automatic pagination handling
- Comprehensive error handling with custom exceptions
- Security-first design with HTTPS enforcement in production
- Flexible token sources (Session, Cache, or Custom)

## Requirements

| Requirement | Version |
|-------------|---------|
| PHP | ^8.3 \| ^8.4 |
| Laravel | ^12.0 |
| GuzzleHTTP | ^7.8 |

## Installation

Install the package via Composer:

```bash
composer require zaimea/groups-sdk-laravel
```

Or add it manually to your `composer.json`:

```json
{
    "require": {
        "zaimea/groups-sdk-laravel": "^1.0"
    }
}
```

## Configuration

### Environment Variables

Add these variables to your `.env` file:

```env

# Optional - API Configuration
ZAIMEA_API_URL=https://resources.click/api/v1/groups/
ZAIMEA_TOKEN_SOURCE=session
ZAIMEA_FORCE_HTTPS=true
ZAIMEA_LOGGING=false
```

### Publish Configuration

Publish the config file to customize settings:

```bash
php artisan vendor:publish --tag=groups-sdk
```

This creates `config/groups_sdk.php` with the following structure:

```php
return [
    'version' => '1.0',

    'api_url' => env('ZAIMEA_API_URL', 'https://resources.click/api/v1/groups/'),

    'auth' => [
        'token_source' => env('ZAIMEA_TOKEN_SOURCE', 'session'), // session, cache, custom
        'session_key' => 'zaimea_access_token',
        'cache' => [
            'key_prefix' => 'zaimea_token_',
        ],
    ],

    'security' => [
        'force_https' => env('ZAIMEA_FORCE_HTTPS', true),
    ],

    'logging' => env('ZAIMEA_LOGGING', false),
];
```

### Token Sources

The SDK supports three token sources:

#### 1. Session (Default)
Token is stored in Laravel session:

```php
// Token is automatically retrieved from session('zaimea_access_token')
Groups::groups(); // Works seamlessly
```

#### 2. Cache
Token is stored in cache (useful for API-only applications):

```env
ZAIMEA_TOKEN_SOURCE=cache
```

```php
// Token is retrieved from cache with key: zaimea_token_{user_id}
```

#### 3. Custom
Extend the SDK to implement your own token retrieval logic.

## Security Considerations

In **production** environment, the SDK enforces strict security rules:

1. `APP_DEBUG` must be `false`
2. `ZAIMEA_FORCE_HTTPS` must be `true`
3. `APP_URL` must start with `https://`

Failure to meet these requirements will throw a `RuntimeException` on boot.

## Verification

After installation, verify the SDK is working:

```php
use Zaimea\SDK\Groups\Facades\Groups;

try {
    $user = Groups::user();
    echo "SDK is working! Authenticated as: {$user->name}";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage();
}
```

## Next Steps

- [Usage Guide](/docs/groups-sdk/{{version}}/usage) - Learn how to use the SDK
- [Examples](/docs/groups-sdk/{{version}}/examples) - See real-world examples
