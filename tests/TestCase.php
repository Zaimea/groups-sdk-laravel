<?php

namespace Zaimea\SDK\Groups\Tests;

use Orchestra\Testbench\TestCase as BaseTestCase;
use Zaimea\SDK\Groups\GroupsServiceProvider;
use Zaimea\SDK\Groups\SDKManager;
use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;

abstract class TestCase extends BaseTestCase
{
    protected function getPackageProviders($app)
    {
        return [
            GroupsServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('groups.credentials.key', 'test_client_id');
        $app['config']->set('groups.credentials.secret', 'test_client_secret');
        $app['config']->set('groups.api_url', 'https://resources.click/api/v1/groups/');
        $app['config']->set('groups.auth.token_source', 'session');
        $app['config']->set('groups.security.force_https', false);
    }

    protected function mockHttpClient(array $responses): HttpClient
    {
        $mock = new MockHandler($responses);
        $handlerStack = HandlerStack::create($mock);
        
        return new HttpClient([
            'handler' => $handlerStack,
            'base_uri' => 'https://resources.click/api/v1/groups/',
            'http_errors' => false,
            'headers' => [
                'Authorization' => 'Bearer test_token',
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ],
        ]);
    }

    protected function mockResponse(array $data, int $status = 200): Response
    {
        return new Response($status, ['Content-Type' => 'application/json'], json_encode($data));
    }

    protected function mockErrorResponse(int $status, string $message = ''): Response
    {
        return new Response($status, ['Content-Type' => 'application/json'], json_encode([
            'message' => $message,
            'error' => $message,
        ]));
    }

    protected function authenticate(string $token = 'test_access_token'): void
    {
        session(['access_token' => $token]);
    }
}