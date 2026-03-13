<?php

declare(strict_types=1);

namespace Zaimea\SDK\Groups;

use GuzzleHttp\Client as HttpClient;
use Zaimea\SDK\Groups\Resources\User;

class SDK
{
    use Actions\ManagesGroups,
        Actions\ManagesGroupRecords,
        Actions\ManagesGroupMembers,
        MakesHttpRequests;

    /**
     * The Groups API Key.
     *
     * @var string
     */
    protected ?string $apiKey = null;

    /**
     * The Guzzle HTTP Client instance.
     *
     * @var \GuzzleHttp\Client
     */
    public ?HttpClient $guzzle = null;

    /**
     * Number of seconds a request is retried.
     *
     * @var int
     */
    public $timeout = 30;

    /**
     * Create a new Groups instance.
     *
     * @return void
     */
    public function __construct(?string $apiKey = null, ?HttpClient $guzzle = null)
    {
        if (! is_null($apiKey)) {
            $this->setApiKey($apiKey, $guzzle);
        }

        if (! is_null($guzzle)) {
            $this->guzzle = $guzzle;
        }
    }

    /**
     * Transform the items of the collection to the given class.
     *
     * @param  array  $collection
     * @param  string  $class
     * @param  array  $extraData
     * @return array
     */
    protected function transformCollection($collection, $class, $extraData = [])
    {
        return array_map(function ($data) use ($class, $extraData) {
            return new $class($data + $extraData, $this);
        }, $collection);
    }

    /**
     * Transform a paginated collection to the given class.
     *
     * @param  array  $response
     * @param  string  $class
     * @param  array  $extraData 
     * @return array
     */
    protected function transformCollectionPaginate($response, $class, $extraData = [])
    {
        $transformedData = [];
        if (isset($response['data']) && is_array($response['data'])) {
            foreach ($response['data'] as $item) {
                $transformedData[] = new $class($item + $extraData, $this);
            }
        }

        return [
            'data' => $transformedData,
            'links' => $response['links'] ?? [
                'first' => null,
                'last' => null,
                'prev' => null,
                'next' => null,
            ],
            'meta' => $response['meta'] ?? [
                'path' => null,
                'per_page' => count($transformedData),
                'next_cursor' => null,
                'prev_cursor' => null,
            ],
            'included' => $response['included'] ?? [],
        ];
    }

    /**
     * Set the api key and setup the guzzle request object.
     *
     * @param  \GuzzleHttp\Client|null  $guzzle
     * @return $this
     */
    public function setApiKey(string $apiKey, $guzzle = null)
    {
        $this->apiKey = $apiKey;

        $this->guzzle = $guzzle ?: new HttpClient([
            'base_uri' => 'https://resources.click/api/v1/groups/',
            'http_errors' => false,
            'timeout' => $this->timeout,
            'headers' => [
                'Authorization' => 'Bearer '.$this->apiKey,
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'User-Agent' => 'Zaimea Groups PHP/3.0',
            ],
        ]);

        return $this;
    }

    /**
     * Set a new timeout.
     *
     * @param  int  $timeout
     * @return $this
     */
    public function setTimeout($timeout)
    {
        $this->timeout = $timeout;

        return $this;
    }

    /**
     * Get the timeout.
     *
     * @return int
     */
    public function getTimeout()
    {
        return $this->timeout;
    }

    /**
     * Get an authenticated user instance.
     *
     * @return \Zaimea\SDK\Groups\Resources\User
     */
    public function user()
    {
        return new User($this->get('user')['user']);
    }
}