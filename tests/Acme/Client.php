<?php

namespace Laravie\Codex\TestCase\Acme;

use Laravie\Codex\Client as BaseClient;

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
     * Construct a new Client.
     */
    public function __construct()
    {
        $this->http = static::makeHttpClient();
    }

    /**
     * Get resource default namespace.
     *
     * @return string
     */
    protected function getResourceNamespace()
    {
        return __NAMESPACE__;
    }
}
