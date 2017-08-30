<?php

namespace Laravie\Codex\Contracts;

interface Client
{
    /**
     * Use custom API Endpoint.
     *
     * @param  string  $endpoint
     *
     * @return $this
     */
    public function useCustomApiEndpoint($endpoint);

    /**
     * Use different API version.
     *
     * @param  string  $version
     *
     * @throws \InvalidArgumentException
     *
     * @return $this
     */
    public function useVersion($version);

    /**
     * Get API endpoint URL.
     *
     * @return string
     */
    public function getApiEndpoint();

    /**
     * Get API default version.
     *
     * @return string
     */
    public function getApiVersion();

    /**
     * Send the HTTP request.
     *
     * @param  string  $method
     * @param  \Psr\Http\Message\UriInterface|string  $uri
     * @param  array  $headers
     * @param  \Psr\Http\Message\StreamInterface|array|null  $body
     *
     * @return \Laravie\Codex\Contracts\Response
     */
    public function send($method, $uri, array $headers = [], $body = []);
}
