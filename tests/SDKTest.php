<?php

namespace Zaimea\SDK\Groups\Tests;

use Zaimea\SDK\Groups\SDK;
use Zaimea\SDK\Groups\SDKManager;
use Zaimea\SDK\Groups\Resources\User;
use Zaimea\SDK\Groups\Resources\Group;
use Zaimea\SDK\Groups\Exceptions\NotFoundException;
use Zaimea\SDK\Groups\Exceptions\ValidationException;
use Zaimea\SDK\Groups\Exceptions\RateLimitExceededException;

class SDKTest extends TestCase
{
    public function test_can_instantiate_sdk()
    {
        $sdk = new SDK('test_api_key');
        $this->assertInstanceOf(SDK::class, $sdk);
    }

    public function test_can_set_api_key()
    {
        $sdk = new SDK();
        $result = $sdk->setApiKey('new_api_key');
        
        $this->assertInstanceOf(SDK::class, $result);
        $this->assertSame($sdk, $result);
    }

    public function test_can_set_timeout()
    {
        $sdk = new SDK('test_key');
        $sdk->setTimeout(60);
        
        $this->assertEquals(60, $sdk->getTimeout());
    }

    public function test_default_timeout_is_30()
    {
        $sdk = new SDK('test_key');
        $this->assertEquals(30, $sdk->getTimeout());
    }

    public function test_can_get_authenticated_user()
    {
        $responseData = [
            'user' => [
                'id' => 1,
                'name' => 'John Doe',
                'email' => 'john@example.com',
                'can_create_groups' => true,
            ]
        ];

        $httpClient = $this->mockHttpClient([
            $this->mockResponse($responseData)
        ]);

        $sdk = new SDK('test_key', $httpClient);
        $user = $sdk->user();

        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals(1, $user->id);
        $this->assertEquals('John Doe', $user->name);
        $this->assertEquals('john@example.com', $user->email);
        $this->assertTrue($user->canCreateGroups);
    }

    public function test_throws_not_found_exception()
    {
        $httpClient = $this->mockHttpClient([
            $this->mockErrorResponse(404)
        ]);

        $sdk = new SDK('test_key', $httpClient);

        $this->expectException(NotFoundException::class);
        $sdk->group(999);
    }

    public function test_throws_validation_exception()
    {
        $httpClient = $this->mockHttpClient([
            new \GuzzleHttp\Psr7\Response(422, ['Content-Type' => 'application/json'], json_encode(
                ['name' => ['The name field is required.']]
            ))
        ]);

        $sdk = new SDK('test_key', $httpClient);

        $this->expectException(ValidationException::class);
        try {
            $sdk->post('groups', ['name' => '']);
        } catch (ValidationException $e) {
            $this->assertArrayHasKey('name', $e->errors());
            throw $e;
        }
    }

    public function test_throws_rate_limit_exception()
    {
        $httpClient = $this->mockHttpClient([
            new \GuzzleHttp\Psr7\Response(429, [
                'Content-Type' => 'application/json',
                'X-RateLimit-Reset' => time() + 3600
            ], json_encode(['message' => 'Too many requests']))
        ]);

        $sdk = new SDK('test_key', $httpClient);

        $this->expectException(RateLimitExceededException::class);
        try {
            $sdk->get('groups');
        } catch (RateLimitExceededException $e) {
            $this->assertNotNull($e->rateLimitResetsAt);
            throw $e;
        }
    }

    public function test_sdk_manager_forwards_calls()
    {
        $this->authenticate();
        
        $manager = app(SDKManager::class);
        $this->assertInstanceOf(SDKManager::class, $manager);
    }

    public function test_sdk_manager_requires_token()
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('No authentication token available');
        
        session()->forget('access_token');
        
        app(SDKManager::class);
    }

    public function test_retry_mechanism()
    {
        $callCount = 0;
        $sdk = new SDK('test_key');
        
        try {
            $result = $sdk->retry(2, function () use (&$callCount) {
                $callCount++;
                return $callCount >= 2 ? ['success' => true] : null;
            }, 0);
            
            $this->assertEquals(['success' => true], $result);
            $this->assertEquals(2, $callCount);
        } catch (\Zaimea\SDK\Groups\Exceptions\TimeoutException $e) {
            $this->fail('Should not throw timeout exception when callback succeeds');
        }
    }

    public function test_retry_throws_timeout_exception()
    {
        $this->expectException(\Zaimea\SDK\Groups\Exceptions\TimeoutException::class);
        
        $sdk = new SDK('test_key');
        
        $sdk->retry(1, function () {
            return null;
        }, 0);
    }
}