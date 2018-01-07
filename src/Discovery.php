<?php

namespace Laravie\Codex;

use Http\Discovery\HttpClientDiscovery;
use Http\Discovery\MessageFactoryDiscovery;
use Http\Client\Common\HttpMethodsClient as HttpClient;

final class Discovery
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
    public static function client(): HttpClient
    {
        return static::$discoveredClient
                    ?? static::$discoveredClient = static::make();
    }

    /**
     * Make a HTTP Client.
     *
     * @return \Http\Client\Common\HttpMethodsClient
     */
    public static function make(): HttpClient
    {
        return new HttpClient(
            HttpClientDiscovery::find(),
            MessageFactoryDiscovery::find()
        );
    }

    /**
     * Make Fresh HTTP client through Discovery.
     *
     * @return \Http\Client\Common\HttpMethodsClient
     */
    public static function refreshClient(): HttpClient
    {
        static::flush();

        return static::client();
    }

    /**
     * Override existing HTTP client.
     *
     * @param  \Http\Client\Common\HttpMethodsClient  $client
     *
     * @return \Http\Client\Common\HttpMethodsClient
     */
    public static function override(HttpClient $client): HttpClient
    {
        static::$discoveredClient = $client;

        return $client;
    }

    /**
     * Flush any existing HTTP Client.
     *
     * @return void
     */
    public static function flush(): void
    {
        static::$discoveredClient = null;
    }
}
