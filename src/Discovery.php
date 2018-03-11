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
    private static $discoveredClient;

    /**
     * Make HTTP client through Discovery.
     *
     * @return \Http\Client\Common\HttpMethodsClient
     */
    public static function client()
    {
        return isset(static::$discoveredClient)
            ? static::$discoveredClient
            : static::$discoveredClient = static::make();
    }

    /**
     * Make a HTTP Client.
     *
     * @return \Http\Client\Common\HttpMethodsClient
     */
    public static function make()
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
    public static function refreshClient()
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
    public static function override(HttpClient $client)
    {
        static::$discoveredClient = $client;

        return $client;
    }

    /**
     * Flush any existing HTTP Client.
     *
     * @return void
     */
    public static function flush()
    {
        static::$discoveredClient = null;
    }
}
