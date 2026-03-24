<?php

declare(strict_types=1);

namespace Zaimea\SDK\Groups;

use Illuminate\Support\ServiceProvider;
use Zaimea\SDK\Groups\Support\ProductionSecurityChecks;
use Zaimea\SDK\Groups\SDKManager;

/**
 * Zaimea Groups SDK for PHP service provider for Laravel applications
 */
class GroupsServiceProvider extends ServiceProvider
{
    const VERSION = '1.0';

    /**
     * Bootstrap the configuration
     *
     * @return void
     */
    public function boot()
    {
        ProductionSecurityChecks::assertForEnvironment((string) app()->environment());

        if ($this->app->runningInConsole()) {
            $this->publishes(
                [__DIR__.'/../config/groups_sdk.php' => config_path('groups_sdk.php')],
                'groups-sdk'
            );
        };
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/groups_sdk.php',
            'groups-sdk'
        );

        $this->app->singleton(SDKManager::class, function ($app) {
            $token = $this->getToken();
            
            if (!$token) {
                throw new \RuntimeException('No authentication token available');
            }
            
            return new SDKManager($token);
        });
    }
    protected function getToken(): ?string
    {
        $source = config('groups_sdk.auth.token_source', 'session');
        $key = config('groups_sdk.auth.session_key', 'groups_token');

        return match ($source) {
            'session' => session($key),
            'cache' => $this->getTokenFromCache(),
            default => null,
        };
    }
    
    protected function getTokenFromCache(): ?string
    {
        $config = config('groups_sdk.auth.cache');
        $userId = auth()->id();
        
        if (!$userId) {
            return null;
        }

        return cache()->store($config['store'] ?? 'default')
                      ->get(($config['key_prefix'] ?? 'groups_token_') . $userId);
    }
}
