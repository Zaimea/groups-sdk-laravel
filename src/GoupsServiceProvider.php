<?php

declare(strict_types=1);

namespace Zaimea\SDK\Groups;

use Zaimea\SDK\Groups;
use Illuminate\Support\ServiceProvider;
use Zaimea\SDK\Groups\Support\ProductionSecurityChecks;

/**
 * Zaimea Groups SDK for PHP service provider for Laravel applications
 */
class GroupsServiceProvider extends ServiceProvider
{
    const VERSION = '1.0';

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

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
                [__DIR__.'/../config/groups.php' => config_path('groups.php')],
                'groups-config'
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
            __DIR__.'/../config/groups.php',
            'groups'
        );

        $this->app->singleton(GroupsClient::class, function ($app) {
            return new GroupsClient();
        });

        $this->app->singleton('groups', function ($app) {
            return $app->make(GroupsClient::class);
        });

        $this->app->alias('groups', GroupsClient::class);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['groups', 'Zaimea\SDK\Groups'];
    }

}
