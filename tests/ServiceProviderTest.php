<?php

namespace Zaimea\SDK\Groups\Tests;

use Zaimea\SDK\Groups\Facades\Groups;
use Zaimea\SDK\Groups\GroupsServiceProvider;
use Zaimea\SDK\Groups\SDKManager;
use Zaimea\SDK\Groups\Support\ProductionSecurityChecks;
use Illuminate\Support\Facades\Cache;

class ServiceProviderTest extends TestCase
{
    public function test_service_provider_is_registered()
    {
        $this->assertTrue(
            $this->app->providerIsLoaded(GroupsServiceProvider::class),
            'GroupsServiceProvider should be loaded'
        );
    }

    public function test_sdk_manager_is_singleton()
    {
        $this->authenticate();
        
        $instance1 = app(SDKManager::class);
        $instance2 = app(SDKManager::class);
        
        $this->assertSame($instance1, $instance2, 'SDKManager should be a singleton');
    }

    public function test_facade_returns_correct_accessor()
    {
        $this->authenticate();
        
        $this->assertEquals(
            SDKManager::class,
            get_class(Groups::getFacadeRoot())
        );
    }

    public function test_config_is_merged()
    {
        $this->assertNotNull(config('groups'));
        $this->assertArrayHasKey('credentials', config('groups'));
        $this->assertArrayHasKey('api_url', config('groups'));
        $this->assertArrayHasKey('auth', config('groups'));
    }

    public function test_token_source_session()
    {
        $this->authenticate('session_token_123');
        
        $this->assertEquals('session_token_123', session('access_token'));
    }

    public function test_token_source_cache()
    {
        config()->set('groups.auth.token_source', 'cache');
        config()->set('groups.auth.cache.key_prefix', 'test_token_');
        
        Cache::put('test_token_1', 'cached_token', 3600);
        
        $token = Cache::get('test_token_1');
        $this->assertEquals('cached_token', $token);
    }

    public function test_production_security_checks_pass_in_non_production()
    {
        $this->assertNull(
            ProductionSecurityChecks::assertForEnvironment('testing')
        );
        $this->assertNull(
            ProductionSecurityChecks::assertForEnvironment('local')
        );
    }

    public function test_production_security_checks_enforce_https()
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('SECURITY_FORCE_HTTPS must be enabled');
        
        config()->set('app.debug', false);
        config()->set('groups.security.force_https', false);
        config()->set('app.url', 'https://example.com');
        
        ProductionSecurityChecks::assertForEnvironment('production');
    }

    public function test_production_security_checks_enforce_debug_false()
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('APP_DEBUG must be false');
        
        config()->set('app.debug', true);
        config()->set('groups.security.force_https', true);
        config()->set('app.url', 'https://example.com');
        
        ProductionSecurityChecks::assertForEnvironment('production');
    }

    public function test_production_security_checks_enforce_https_url()
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('APP_URL must use https://');
        
        config()->set('app.debug', false);
        config()->set('groups.security.force_https', true);
        config()->set('app.url', 'http://example.com');
        
        ProductionSecurityChecks::assertForEnvironment('production');
    }

    public function test_production_security_checks_pass_when_configured_correctly()
    {
        config()->set('app.debug', false);
        config()->set('groups.security.force_https', true);
        config()->set('app.url', 'https://example.com');
        
        $this->assertNull(
            ProductionSecurityChecks::assertForEnvironment('production')
        );
    }

    public function test_version_constant()
    {
        $this->assertEquals('1.0', GroupsServiceProvider::VERSION);
    }
}