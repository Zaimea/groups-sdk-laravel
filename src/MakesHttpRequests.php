<?php

declare(strict_types=1);

namespace Zaimea\SDK\Groups;

use Exception;
use Psr\Http\Message\ResponseInterface;
use Zaimea\SDK\Groups\Exceptions\FailedActionException;
use Zaimea\SDK\Groups\Exceptions\ForbiddenException;
use Zaimea\SDK\Groups\Exceptions\NotFoundException;
use Zaimea\SDK\Groups\Exceptions\RateLimitExceededException;
use Zaimea\SDK\Groups\Exceptions\TimeoutException;
use Zaimea\SDK\Groups\Exceptions\ValidationException;
use Zaimea\SDK\Groups\Support\SecurityAudit;

trait MakesHttpRequests
{
    /**
     * Make a GET request to Groups servers and return the response.
     *
     * @param  string  $uri
     * @param  array   $payload
     * @return mixed
     */
    public function get($uri, array $payload = [])
    {
        return $this->request('GET', $uri, $payload);
    }

    /**
     * Make a POST request to Groups servers and return the response.
     *
     * @param  string  $uri
     * @param  array   $payload
     * @return mixed
     */
    public function post($uri, array $payload = [])
    {
        return $this->request('POST', $uri, $payload);
    }

    /**
     * Make a PUT request to Groups servers and return the response.
     *
     * @param  string  $uri
     * @param  array   $payload
     * @return mixed
     */
    public function put($uri, array $payload = [])
    {
        return $this->request('PUT', $uri, $payload);
    }

    /**
     * Make a DELETE request to Groups servers and return the response.
     *
     * @param  string  $uri
     * @param  array   $payload
     * @return mixed
     */
    public function delete($uri, array $payload = [])
    {
        return $this->request('DELETE', $uri, $payload);
    }

    /**
     * Make request to Groups servers and return the response.
     *
     * @param  string  $verb
     * @param  string  $uri
     * @param  array   $payload
     * @return mixed
     */
    protected function request($verb, $uri, array $payload = [])
    {
        if (in_array(strtoupper($verb), ['GET', 'DELETE'])) {
            $payload = ['query' => $payload];
        } else {
            $payload['json'] = empty($payload) ? [] :  $payload;
        }

        $response = $this->guzzle->request($verb, $uri, $payload);

        $statusCode = $response->getStatusCode();

        if ($statusCode < 200 || $statusCode > 299) {
            if (config('groups_sdk.logging', true)) {
                if ($statusCode === 401 || $statusCode === 403) {
                    SecurityAudit::log('api.error', [
                        'status' => $statusCode,
                        'uri' => $uri,
                    ]);
                }
            }
            return $this->handleRequestError($response);
        }

        $responseBody = (string) $response->getBody();

        return json_decode($responseBody, true) ?: $responseBody;
    }

    /**
     * Handle the request error.
     *
     * @return void
     *
     * @throws \Exception
     * @throws \Zaimea\SDK\Groups\Exceptions\FailedActionException
     * @throws \Zaimea\SDK\Groups\Exceptions\ForbiddenException
     * @throws \Zaimea\SDK\Groups\Exceptions\NotFoundException
     * @throws \Zaimea\SDK\Groups\Exceptions\ValidationException
     * @throws \Zaimea\SDK\Groups\Exceptions\RateLimitExceededException
     */
    protected function handleRequestError(ResponseInterface $response)
    {
        if ($response->getStatusCode() == 422) {
            throw new ValidationException(json_decode((string) $response->getBody(), true));
        }

        if ($response->getStatusCode() === 403) {
            throw new ForbiddenException((string) $response->getBody());
        }

        if ($response->getStatusCode() == 404) {
            throw new NotFoundException;
        }

        if ($response->getStatusCode() == 400) {
            throw new FailedActionException((string) $response->getBody());
        }

        if ($response->getStatusCode() === 429) {
            throw new RateLimitExceededException(
                $response->hasHeader('x-ratelimit-reset')
                    ? (int) $response->getHeader('x-ratelimit-reset')[0]
                    : null
            );
        }

        throw new Exception((string) $response->getBody());
    }

    /**
     * Retry the callback or fail after x seconds.
     *
     * @param  int  $timeout
     * @param  callable  $callback
     * @param  int  $sleep
     * @return mixed
     *
     * @throws \Zaimea\SDK\Groups\Exceptions\TimeoutException
     */
    public function retry($timeout, $callback, $sleep = 5)
    {
        $start = time();

        beginning:

        if ($output = $callback()) {
            return $output;
        }

        if (time() - $start < $timeout) {
            sleep($sleep);

            goto beginning;
        }

        if ($output === null || $output === false) {
            $output = [];
        }

        if (! is_array($output)) {
            $output = [$output];
        }

        throw new TimeoutException($output);
    }
}