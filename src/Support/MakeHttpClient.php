<?php

namespace Laravie\Codex\Support;

use Laravie\Codex\Discovery;
use Http\Client\Common\HttpMethodsClient;

trait MakeHttpClient
{
    /**
     * Make HTTP client through Discovery.
     *
     * @return \Http\Client\Common\HttpMethodsClient
     */
    protected static function makeHttpClient(): HttpMethodsClient
    {
        return Discovery::client();
    }

    /**
     * Make Fresh HTTP client through Discovery.
     *
     * @return \Http\Client\Common\HttpMethodsClient
     */
    protected static function makeFreshHttpClient(): HttpMethodsClient
    {
        return Discovery::refreshClient();
    }
}
