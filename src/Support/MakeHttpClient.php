<?php

namespace Laravie\Codex\Support;

use Laravie\Codex\Discovery;

trait MakeHttpClient
{
    /**
     * Make HTTP client through Discovery.
     *
     * @return \Http\Client\Common\HttpMethodsClient
     */
    protected static function makeHttpClient()
    {
        return Discovery::client();
    }

    /**
     * Make Fresh HTTP client through Discovery.
     *
     * @return \Http\Client\Common\HttpMethodsClient
     */
    protected static function makeFreshHttpClient()
    {
        return Discovery::refreshClient();
    }
}
