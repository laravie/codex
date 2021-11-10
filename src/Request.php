<?php

namespace Laravie\Codex;

use Psr\Http\Message\ResponseInterface;

/**
 * @property \Laravie\Codex\Client $client
 */
abstract class Request extends Common\Request
{
    use Support\Responsable,
        Support\Versioning;

    /**
     * Version namespace.
     *
     * @var string
     */
    protected $version;

    /**
     * Automatically validate response.
     *
     * @var bool
     */
    protected $validateResponseAutomatically = true;

    /**
     * Construct a new Request.
     */
    public function __construct()
    {
        if (method_exists($this, 'sanitizeWith') && $this instanceof Contracts\Filterable) {
            $this->setFilterable($this->sanitizeWith());
        }
    }

    /**
     * Send API request.
     *
     * @param  string  $method
     * @param  \Laravie\Codex\Contracts\Endpoint|string  $path
     * @param  array<string, mixed>  $headers
     * @param  \Psr\Http\Message\StreamInterface|\Laravie\Codex\Common\Payload|array|null  $body
     *
     * @return \Laravie\Codex\Contracts\Response
     */
    protected function send(string $method, $path, array $headers = [], $body = []): Contracts\Response
    {
        if ($this instanceof Contracts\Filterable) {
            /** @var \Psr\Http\Message\StreamInterface|\Laravie\Codex\Common\Payload|array|null $body */
            $body = $this->filterRequest($body);
        }

        $endpoint = $path instanceof Contracts\Endpoint
            ? $this->getApiEndpoint($path->getPath())->addQuery($path->getQuery())
            : $this->getApiEndpoint($path);

        $message = $this->responseWith(
            $this->client->send($method, $endpoint, $headers, $body)
        );

        return $this->interactsWithResponse($message);
    }

    /**
     * Resolve the responder class.
     *
     * @param  \Psr\Http\Message\ResponseInterface  $message
     *
     * @return \Laravie\Codex\Contracts\Response
     */
    protected function responseWith(ResponseInterface $message): Contracts\Response
    {
        return new Response($message);
    }

    /**
     * Get API Header.
     *
     * @return array<string, mixed>
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
     * @param  array<string, mixed>  $headers
     *
     * @return array<string, mixed>
     */
    final protected function mergeApiHeaders(array $headers = []): array
    {
        return array_merge($this->getApiHeaders(), $headers);
    }

    /**
     * Merge API Body.
     *
     * @param  array  $body
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
     * @param  array<int, string>|string  $path
     *
     * @return \Laravie\Codex\Contracts\Endpoint
     */
    protected function getApiEndpoint($path = []): Contracts\Endpoint
    {
        return new Endpoint($this->client->getApiEndpoint() ?? '', $path);
    }
}
