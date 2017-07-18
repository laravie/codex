<?php

namespace Laravie\Codex;

use Http\Discovery\HttpClientDiscovery;
use Http\Discovery\MessageFactoryDiscovery;
use Http\Client\Common\HttpMethodsClient as HttpClient;

class Discovery
{
    /**
     * Cache discovered HTTP Client.
     *
     * @var
     */
    protected static $discoveredClient;

    /**
     * Make HTTP client through Discovery.
     *
     * @return \Http\Client\Common\HttpMethodsClient
     */
    public static function client()
    {
        return isset(static::$discoveredClient)
            ? static::$discoveredClient
            : static::$discoveredClient = new HttpClient(
                HttpClientDiscovery::find(),
                MessageFactoryDiscovery::find()
            );
    }

    /**
     * Make Fresh HTTP client through Discovery.
     *
     * @return \Http\Client\Common\HttpMethodsClient
     */
    public static function refreshClient()
    {
        unset(static::$discoveredClient);

        return static::client();
    }
}
