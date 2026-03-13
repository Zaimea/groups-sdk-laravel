<?php

declare(strict_types=1);

namespace Zaimea\SDK\Groups;

use GuzzleHttp\Client as HttpClient;
use Illuminate\Support\Traits\ForwardsCalls;
use Zaimea\SDK\Groups\SDK;

/**
 * @mixin \Zaimea\SDK\Groups\SDK
 */
class SDKManager
{
    use ForwardsCalls;

    /**
     * The SDK instance.
     *
     * @var \Zaimea\SDK\Groups\SDK
     */
    protected $sdk;

    /**
     * Create a new SDK manager instance.
     *
     * @param  string  $token
     */
    public function __construct($token, ?HttpClient $guzzle = null)
    {
        $this->sdk = new SDK($token, $guzzle);
    }

    /**
     * Dynamically pass methods to the SDK instance.
     *
     * @return mixed
     */
    public function __call(string $method, array $parameters)
    {
        return $this->forwardCallTo($this->sdk, $method, $parameters);
    }
}