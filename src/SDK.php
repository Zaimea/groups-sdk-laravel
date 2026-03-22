<?php

declare(strict_types=1);

namespace Zaimea\SDK\Groups;

use GuzzleHttp\Client as HttpClient;
use Zaimea\SDK\Groups\Resources\User;
use Illuminate\Pagination\AbstractPaginator;

class SDK
{
    use Actions\ManagesGroups,
        Actions\ManagesGroupRecords,
        Actions\ManagesGroupMembers,
        Actions\ManagesGroupClients,
        Actions\ManagesClients,
        Actions\ManagesGroupColors,
        Actions\ManagesGroupCounts,
        Actions\ManagesInvitations,
        Actions\ManagesGroupHolidays,
        Actions\ManagesGroupLockings,
        Actions\ManagesGroupMedicals,
        Actions\ManagesGroupMonthlyQuotas,
        Actions\ManagesGroupProjects,
        Actions\ManagesGroupReports,
        Actions\ManagesGroupRoles,
        Actions\ManagesGroupTasks,
        Actions\ManagesGroupTemplates,
        Actions\ManagesGroupVacations,
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
    public int $timeout = 30;

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
     * @return array|AbstractPaginator
     */
    protected function transformCollection(array $collection, string $class, array $extraData = []): array|AbstractPaginator
    {
        if (is_array($collection) && isset($collection['data']) && isset($collection['meta'])) {
            $collection['data'] = array_map(function ($data) use ($class, $extraData) {
                return new $class($data + $extraData, $this);
            }, $collection['data']);
            
            return $collection;
        }
        
        if ($collection instanceof AbstractPaginator) {
            $collection->getCollection()->transform(function ($data) use ($class, $extraData) {
                return new $class($data + $extraData, $this);
            });
            
            return $collection;
        }
        
        return array_map(function ($data) use ($class, $extraData) {
            return new $class($data + $extraData, $this);
        }, $collection);
    }

    /**
     * Set the api key and setup the guzzle request object.
     *
     * @param  \GuzzleHttp\Client|null  $guzzle
     * @return $this
     */
    public function setApiKey(string $apiKey, ?HttpClient $guzzle = null): self
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
                'User-Agent' => 'Zaimea Groups PHP/1.0',
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
    public function setTimeout(int $timeout): self
    {
        $this->timeout = $timeout;

        return $this;
    }

    /**
     * Get the timeout.
     *
     * @return int
     */
    public function getTimeout(): int
    {
        return $this->timeout;
    }

    /**
     * Get an authenticated user instance.
     *
     * @return \Zaimea\SDK\Groups\Resources\User
     */
    public function user(): User
    {
        return new User($this->get('user')['user']);
    }
}