<?php

namespace Laravie\Codex\Tests\Acme;

use Laravie\Codex\Common\Discovery;
use Laravie\Codex\Client as BaseClient;
use Http\Client\Common\HttpMethodsClient as HttpClient;

class Client extends BaseClient
{
    /**
     * The API endpoint.
     *
     * @var string
     */
    protected $apiEndpoint = 'https://acme.laravie/';

    /**
     * List of supported API versions.
     *
     * @var array
     */
    protected $supportedVersions = [
        'v1' => 'One',
        'v2' => 'Two',
    ];

    /**
     * API Key.
     *
     * @var string
     */
    protected $apiKey;

    /**
     * Construct a new Billplz Client.
     *
     * @param \Http\Client\Common\HttpMethodsClient  $http
     * @param string  $apiKey
     */
    public function __construct(HttpClient $http, $apiKey)
    {
        $this->http = $http;
        $this->apiKey = $apiKey;
    }

    /**
     * Make a client.
     *
     * @param string  $apiKey
     *
     * @return $this
     */
    public static function make($apiKey)
    {
        return new static(Discovery::client(), $apiKey);
    }

    /**
     * Get API Key.
     *
     * @return string
     */
    public function getApiKey()
    {
        return $this->apiKey;
    }

    /**
     * Get resource default namespace.
     *
     * @return string
     */
    protected function getResourceNamespace(): string
    {
        return __NAMESPACE__;
    }
}
