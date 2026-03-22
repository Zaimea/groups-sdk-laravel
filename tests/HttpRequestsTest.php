<?php

namespace Zaimea\SDK\Groups\Tests;

use Zaimea\SDK\Groups\SDK;
use Zaimea\SDK\Groups\Exceptions\NotFoundException;
use Zaimea\SDK\Groups\Exceptions\ValidationException;
use Zaimea\SDK\Groups\Exceptions\ForbiddenException;
use Zaimea\SDK\Groups\Exceptions\FailedActionException;
use Zaimea\SDK\Groups\Exceptions\RateLimitExceededException;

class HttpRequestsTest extends TestCase
{
    private function createSDKWithMock(array $responses): SDK
    {
        $httpClient = $this->mockHttpClient($responses);
        return new SDK('test_key', $httpClient);
    }

    public function test_can_make_get_request()
    {
        $sdk = $this->createSDKWithMock([
            $this->mockResponse(['data' => 'test'])
        ]);

        $response = $sdk->get('test/endpoint', ['param' => 'value']);

        $this->assertEquals(['data' => 'test'], $response);
    }

    public function test_can_make_post_request()
    {
        $sdk = $this->createSDKWithMock([
            $this->mockResponse(['created' => true])
        ]);

        $response = $sdk->post('test/endpoint', ['name' => 'Test']);

        $this->assertEquals(['created' => true], $response);
    }

    public function test_can_make_put_request()
    {
        $sdk = $this->createSDKWithMock([
            $this->mockResponse(['updated' => true])
        ]);

        $response = $sdk->put('test/endpoint', ['name' => 'Updated']);

        $this->assertEquals(['updated' => true], $response);
    }

    public function test_can_make_delete_request()
    {
        $sdk = $this->createSDKWithMock([
            $this->mockResponse(['deleted' => true])
        ]);

        $response = $sdk->delete('test/endpoint', ['id' => 123]);

        $this->assertEquals(['deleted' => true], $response);
    }

    public function test_handles_404_error()
    {
        $sdk = $this->createSDKWithMock([
            $this->mockErrorResponse(404)
        ]);

        $this->expectException(NotFoundException::class);
        $this->expectExceptionMessage('The resource you are looking for could not be found.');

        $sdk->get('nonexistent');
    }

    public function test_handles_422_validation_error()
    {
        $errorData = ['name' => ['The name field is required.']];
        
        $sdk = $this->createSDKWithMock([
            new \GuzzleHttp\Psr7\Response(422, ['Content-Type' => 'application/json'], json_encode($errorData))
        ]);

        $this->expectException(ValidationException::class);

        try {
            $sdk->post('groups', []);
        } catch (ValidationException $e) {
            $this->assertEquals($errorData, $e->errors());
            throw $e;
        }
    }

    public function test_handles_403_forbidden_error()
    {
        $sdk = $this->createSDKWithMock([
            $this->mockErrorResponse(403, 'Access denied')
        ]);

        $this->expectException(ForbiddenException::class);
        $sdk->get('restricted');
    }

    public function test_handles_400_bad_request_error()
    {
        $sdk = $this->createSDKWithMock([
            $this->mockErrorResponse(400, 'Invalid parameters')
        ]);

        $this->expectException(FailedActionException::class);
        $sdk->post('action', ['invalid' => 'data']);
    }

    public function test_handles_429_rate_limit_error()
    {
        $resetTime = time() + 3600;
        
        $sdk = $this->createSDKWithMock([
            new \GuzzleHttp\Psr7\Response(429, [
                'Content-Type' => 'application/json',
                'X-RateLimit-Reset' => $resetTime
            ], json_encode(['message' => 'Too many requests']))
        ]);

        $this->expectException(RateLimitExceededException::class);

        try {
            $sdk->get('groups');
        } catch (RateLimitExceededException $e) {
            $this->assertEquals($resetTime, $e->rateLimitResetsAt);
            throw $e;
        }
    }

    public function test_handles_generic_error()
    {
        $sdk = $this->createSDKWithMock([
            $this->mockErrorResponse(500, 'Internal server error')
        ]);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Internal server error');

        $sdk->get('groups');
    }
}