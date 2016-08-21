<?php

namespace Laravie\Codex;

use GuzzleHttp\Psr7\Uri;

abstract class Request
{
    use WithSanitizer;

    /**
     * Version namespace.
     *
     * @var string
     */
    protected $version;

    /**
     * The Codex client.
     *
     * @var \Laravie\Codex\Client
     */
    protected $client;

    /**
     * Construct a new Collection.
     *
     * @param \Laravie\Codex\Client  $client
     * @param \Laravie\Codex\Sanitizer|null  $sanitizer
     */
    public function __construct(Client $client, Sanitizer $sanitizer = null)
    {
        $this->client = $client;

        $this->setSanitizer($sanitizer);
    }

    /**
     * Get API version.
     *
     * @return string
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * Send API request.
     *
     * @param  string  $method
     * @param  string  $path
     * @param  array  $headers
     * @param  array  $body
     *
     * @return \Laravie\Codex\Reponse
     */
    protected function send($method, $path, array $headers = [], array $body = [])
    {
        if ($this->hasSanitizer()) {
            $body = $this->getSanitizer()->from($body);
        }

        return $this->client->send($method, $this->getUriEndpoint($path), $headers, $body)
                    ->setSanitizer($this->getSanitizer());
    }

    /**
     * Get URI Endpoint.
     *
     * @param  string  $endpoint
     *
     * @return \GuzzleHttp\Psr7\Uri
     */
    abstract protected function getUriEndpoint($endpoint);
}
