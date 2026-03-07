<?php

declare(strict_types=1);

namespace Zaimea\SDK\Groups;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use Illuminate\Support\Facades\Log;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Zaimea\SDK\Groups\Exceptions\ApiException;
use Zaimea\SDK\Groups\Exceptions\AuthenticationException;
use Zaimea\SDK\Groups\Exceptions\NotFoundException;
use Zaimea\SDK\Groups\Exceptions\ValidationException;

class GroupsClient
{
    protected Client $httpClient;
    protected array $config;
    protected ?string $token = null;

    public function __construct(array $config = [])
    {
        $this->config = array_merge($this->defaultConfig(), $config);
        $this->httpClient = $this->createHttpClient();
    }
    
    protected function defaultConfig(): array
    {
        return [
            'base_uri' => config('groups.api.base_url', 'https://resources.click/api/v1'),
            'timeout' => config('groups.api.timeout', 30),
            'verify' => config('groups.security.verify_ssl', true),
        ];
    }

    protected function createHttpClient(): Client
    {
        $stack = HandlerStack::create();

        // Retry middleware
        if ($this->config['features']['auto_retry'] ?? true) {
            $stack->push($this->retryMiddleware());
        }

        // Logging middleware
        if ($this->config['features']['logging'] ?? false) {
            $stack->push($this->loggingMiddleware());
        }

        return new Client(array_merge($this->config, ['handler' => $stack]));
    }

    protected function retryMiddleware(): callable
    {
        return Middleware::retry(
            function ($retries, RequestInterface $request, ?ResponseInterface $response = null, ?GuzzleException $exception = null) {
                // Retry on connection errors or 5xx responses
                if ($exception !== null) {
                    return $retries < 3;
                }
                
                if ($response !== null && $response->getStatusCode() >= 500) {
                    return $retries < 3;
                }

                return false;
            },
            function ($retries) {
                // Exponential backoff: 100ms, 200ms, 400ms
                return 100 * (2 ** $retries);
            }
        );
    }

    protected function loggingMiddleware(): callable
    {
        return Middleware::tap(function (RequestInterface $request) {
            Log::debug('Zaimea Groups API Request', [
                'method' => $request->getMethod(),
                'uri' => (string) $request->getUri(),
            ]);
        });
    }

    /**
     * Set authentication token
     */
    public function setToken(string $token): self
    {
        $this->token = $token;
        return $this;
    }

    /**
     * Get token from configured source
     */
    public function getToken(): ?string
    {
        if ($this->token !== null) {
            return $this->token;
        }

        $source = config('groups.auth.token_source', 'session');

        return match ($source) {
            'session' => session(config('groups.auth.session_key')),
            'cache' => $this->getTokenFromCache(),
            default => null,
        };
    }

    protected function getTokenFromCache(): ?string
    {
        $config = config('groups.auth.cache');
        $userId = auth()->id();
        
        if (!$userId) {
            return null;
        }

        $key = $config['key_prefix'] . $userId;

        return cache()->store($config['store'])->get($key);
    }

    /**
     * Make API request
     *
     * @throws ApiException
     * @throws AuthenticationException
     * @throws NotFoundException
     * @throws ValidationException
     */
    public function request(string $method, string $endpoint, array $data = []): array
    {
        $token = $this->getToken();

        if (!$token) {
            throw new AuthenticationException('No authentication token available');
        }

        $options = [
            'headers' => [
                'Authorization' => 'Bearer ' . $token,
                'Accept' => 'application/json',
                'X-Requested-With' => 'XMLHttpRequest',
            ],
        ];

        if (!empty($data) && in_array(strtoupper($method), ['POST', 'PUT', 'PATCH'])) {
            $options['json'] = $data;
        } elseif (!empty($data)) {
            $options['query'] = $data;
        }

        try {
            $response = $this->httpClient->request($method, ltrim($endpoint, '/'), $options);
            
            $body = json_decode($response->getBody()->getContents(), true);
            
            return $body ?? [];

        } catch (GuzzleException $e) {
            $this->handleException($e);
        }
    }

    /**
     * Handle Guzzle exceptions and convert to SDK exceptions
     */
    protected function handleException(GuzzleException $e): never
    {
        if ($e instanceof \GuzzleHttp\Exception\ClientException) {
            $status = $e->getResponse()->getStatusCode();
            $body = json_decode($e->getResponse()->getBody()->getContents(), true);

            throw match ($status) {
                401 => new AuthenticationException($body['message'] ?? 'Unauthorized', 401, $e),
                403 => new AuthenticationException($body['message'] ?? 'Forbidden', 403, $e),
                404 => new NotFoundException($body['message'] ?? 'Not found', 404, $e),
                422 => new ValidationException($body['message'] ?? 'Validation failed', $body['errors'] ?? [], 422, $e),
                default => new ApiException($body['message'] ?? 'API error', $status, $e),
            };
        }

        if ($e instanceof \GuzzleHttp\Exception\ConnectException) {
            throw new ApiException('Connection failed: ' . $e->getMessage(), 0, $e);
        }

        throw new ApiException('Request failed: ' . $e->getMessage(), 0, $e);
    }

    // ========== API Methods ==========

    /**
     * Get all groups for authenticated user
     */
    public function getGroups(): array
    {
        return $this->request('GET', 'user/get/groups');
    }

    /**
     * Get group records
     */
    public function getGroupRecords(array $filters = []): array
    {
        return $this->request('GET', 'user/get/groups/records', $filters);
    }

    /**
     * Create group
     * 
     * @param string $name
     * @param array $extraDate
     */
    public function createGroup(string $name, array $extraData = []): array
    {
        $payload = [
            'name' => $name,
        ];

        if (!empty($extraData)) {
            $payload['form'] = array_merge(['name' => $name], $extraData);
        }

        return $this->request('POST', 'groups/create', $payload);
    }

    /**
     * Get group details
     */
    public function getGroup(int $groupId): array
    {
        return $this->request('GET', 'groups/read', ['group' => $groupId]);
    }

    /**
     * Update group name/description
     * 
     * @param int $groupId
     * @param array $data ['name' => '...', 'description' => '...']
     */
    public function updateGroup(int $groupId, array $data): array
    {
        $payload = [
            'group' => $groupId,
        ];

        if (isset($data['name'])) {
            $payload['name'] = $data['name'];
        }
        if (isset($data['description'])) {
            $payload['description'] = $data['description'];
        }

        return $this->request('PUT', 'groups/update', $payload);
    }

    /**
     * Update group with form data
     * 
     * @param int $groupId
     * @param array $formData
     */
    public function updateGroupForm(int $groupId, array $formData): array
    {
        return $this->request('PUT', 'groups/update', [
            'group' => $groupId,
            'form' => $formData,
        ]);
    }

    /**
     * Delete group
     */
    public function deleteGroup(int $groupId): array
    {
        return $this->request('DELETE', 'groups/delete', ['id' => $groupId]);
    }

    /**
     * Get group members
     */
    public function getGroupMembers(int $groupId): array
    {
        return $this->request('GET', 'groups/members/all', ['group' => $groupId]);
    }

    /**
     * Get group projects
     */
    public function getGroupProjects(int $groupId): array
    {
        return $this->request('GET', 'groups/projects/all', ['group' => $groupId]);
    }

    /**
     * Generic method for any endpoint with pattern [param1, param2]
     * 
     * @param string $method HTTP method
     * @param string $endpoint API endpoint
     * @param array $params Parameters to send (will be normalized to JSON body or query string)
     */
    public function call(string $method, string $endpoint, array $params = []): array
    {
        // Convert numeric array to associative array for JSON
        $payload = $this->normalizeParams($params);

        return $this->request($method, $endpoint, $payload);
    }

    /**
     * Normalize parameters for API
     * 
     * Ex: ['group' => 1, 'name' => 'Test'] or [1, 'Test'] → {'group': 1, 'name': 'Test'}
     */
    protected function normalizeParams(array $params): array
    {
        $normalized = [];

        foreach ($params as $key => $value) {
            // If key is numeric, assume it's parameter order (e.g. [1, 'Test'] → ['group' => 1, 'name' => 'Test'])
            if (is_int($key)) {
                $normalized[$this->getParamNameByIndex($key)] = $value;
            } else {
                $normalized[$key] = $value;
            }
        }

        return $normalized;
    }

    /**
     * Map index → param name (for common endpoints)
     */
    protected function getParamNameByIndex(int $index): string
    {
        return match ($index) {
            0 => 'group',
            1 => 'name',
            2 => 'form',
            default => 'param_' . $index,
        };
    }
}