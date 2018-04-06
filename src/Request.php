<?php

namespace Laravie\Codex;

abstract class Request implements Contracts\Request
{
    use Support\WithSanitizer;

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
     * Construct a new Request.
     */
    public function __construct()
    {
        if (method_exists($this, 'sanitizeWith')) {
            $this->setSanitizer($this->sanitizeWith());
        }
    }

    /**
     * Create Endpoint instance.
     *
     * @param  string $uri
     * @param  string|array  $path
     * @param  array  $query
     *
     * @return \Laravie\Codex\Contracts\Endpoint
     */
    public static function to(string $uri, $path = [], array $query = []): Contracts\Endpoint
    {
        return new Endpoint($uri, $path, $query);
    }

    /**
     * Set Codex Client.
     *
     * @param  \Laravie\Codex\Contracts\Client  $client
     *
     * @return $this
     */
    final public function setClient(Contracts\Client $client): self
    {
        $this->client = $client;

        return $this;
    }

    /**
     * Get API version.
     *
     * @return string
     */
    final public function getVersion(): string
    {
        return $this->version;
    }

    /**
     * Send API request.
     *
     * @param  string  $method
     * @param  \Laravie\Codex\Contracts\Endpoint|string  $path
     * @param  array  $headers
     * @param  \Psr\Http\Message\StreamInterface|array|null  $body
     *
     * @return \Laravie\Codex\Contracts\Response
     */
    protected function send(string $method, $path, array $headers = [], $body = []): Contracts\Response
    {
        $body = $this->sanitizeFrom($body);

        $endpoint = ($path instanceof Contracts\Endpoint)
                        ? $this->getApiEndpoint($path->getPath())->addQuery($path->getQuery())
                        : $this->getApiEndpoint($path);

        return $this->client->send($method, $endpoint, $headers, $body)
                    ->setSanitizer($this->getSanitizer())
                    ->validate();
    }

    /**
     * Get API Header.
     *
     * @return array
     */
    protected function getApiHeaders(): array
    {
        return [];
    }

    /**
     * Get API Body.
     *
     * @return array
     */
    protected function getApiBody(): array
    {
        return [];
    }

    /**
     * Merge API Headers.
     *
     * @param  array  $headers
     *
     * @return array
     */
    final protected function mergeApiHeaders(array $headers = []): array
    {
        return array_merge($this->getApiHeaders(), $headers);
    }

    /**
     * Merge API Body.
     *
     * @param  array  $headers
     *
     * @return array
     */
    final protected function mergeApiBody(array $body = []): array
    {
        return array_merge($this->getApiBody(), $body);
    }

    /**
     * Get API Endpoint.
     *
     * @param  string|array  $path
     *
     * @return \Laravie\Codex\Contracts\Endpoint
     */
    protected function getApiEndpoint($path = []): Contracts\Endpoint
    {
        return new Endpoint($this->client->getApiEndpoint(), $path);
    }
}
