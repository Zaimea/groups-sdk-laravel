---
title: How to install package
description: How to install package
github: https://github.com/zaimea/groups-sdk-laravel/edit/main/docs/
onThisArticle: true
sidebar: true
rightbar: true
---

# Zaimea Groups SDK for Laravel

[[TOC]]

## Introduction

`zaimea/groups-sdk-laravel` is a PHP SDK for interacting with the Zaimea Groups API. It provides a fluent, Laravel-friendly interface for managing groups, members, records, and aggregates.

- Supported: Laravel 10+ / PHP 8.1+
- Built on GuzzleHTTP
- JSON:API compatible
- Pagination support with automatic page traversal

## Installation

You can install the package via composer:

```bash
composer require zaimea/groups-sdk-laravel
```

or via composer.json:

```bash
"repositories": [
    {
        "type": "vcs",
        "url": "https://github.com/zaimea/groups-sdk-laravel"
    }
]
```

## Configuration

Add environment variables
In `.env`:

```bash
ZAIMEA_GROUPS_API_KEY=your_api_key
ZAIMEA_GROUPS_BASE_URI=https://resources.click/api/v1/groups/
ZAIMEA_GROUPS_TIMEOUT=30
```

## Publish config (optional)

```bash
php artisan vendor:publish --tag=groups-sdk-config
```