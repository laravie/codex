<?php

namespace Laravie\Codex;

use Laravie\Codex\Common\Endpoint;
use Psr\Http\Message\ResponseInterface;
use Laravie\Codex\Filter\Support\WithFilterable;
use Laravie\Codex\Contracts\Client as ClientContract;
use Laravie\Codex\Contracts\Request as RequestContract;
use Laravie\Codex\Contracts\Endpoint as EndpointContract;
use Laravie\Codex\Contracts\Response as ResponseContract;
use Laravie\Codex\Contracts\Filterable as FilterableContract;

abstract class Request implements RequestContract
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
     * The Codex client.
     *
     * @var \Laravie\Codex\Contracts\Client
     */
    protected $client;

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
        if (\method_exists($this, 'sanitizeWith') && $this instanceof FilterableContract) {
            $this->setFilterable($this->sanitizeWith());
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
    public static function to(string $uri, $path = [], array $query = []): EndpointContract
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
    final public function setClient(ClientContract $client): self
    {
        $this->client = $client;

        return $this;
    }

    /**
     * Send API request.
     *
     * @param  string  $method
     * @param  \Laravie\Codex\Contracts\Endpoint|string  $path
     * @param  array  $headers
     * @param  \Psr\Http\Message\StreamInterface|\Laravie\Codex\Common\Payload|array|null  $body
     *
     * @return \Laravie\Codex\Contracts\Response
     */
    protected function send(string $method, $path, array $headers = [], $body = []): ResponseContract
    {
        if ($this instanceof FilterableContract) {
            $body = $this->filterRequest($body);
        }

        $endpoint = ($path instanceof EndpointContract)
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
    protected function responseWith(ResponseInterface $message): ResponseContract
    {
        return new Response($message);
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
        return \array_merge($this->getApiHeaders(), $headers);
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
        return \array_merge($this->getApiBody(), $body);
    }

    /**
     * Get API Endpoint.
     *
     * @param  string|array  $path
     *
     * @return \Laravie\Codex\Contracts\Endpoint
     */
    protected function getApiEndpoint($path = []): EndpointContract
    {
        return new Endpoint($this->client->getApiEndpoint(), $path);
    }
}
