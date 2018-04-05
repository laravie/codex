<?php

namespace Laravie\Codex\Support;

use GuzzleHttp\Psr7\Uri;
use Laravie\Codex\Endpoint;
use Psr\Http\Message\UriInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\ResponseInterface;
use Laravie\Codex\Contracts\Endpoint as EndpointContract;
use Laravie\Codex\Contracts\Response as ResponseContract;

trait HttpClient
{
    /**
     * Http Client instance.
     *
     * @var \Http\Client\Common\HttpMethodsClient
     */
    protected $http;

    /**
     * List of HTTP requests.
     *
     * @var array
     */
    protected $httpRequestQueries = [];

    /**
     * Send the HTTP request.
     *
     * @param  string  $method
     * @param  \Laravie\Codex\Contracts\Endpoint|\Psr\Http\Message\UriInterface|string  $uri
     * @param  array  $headers
     * @param  \Psr\Http\Message\StreamInterface|array|null  $body
     *
     * @return \Laravie\Codex\Contracts\Response
     */
    public function send(string $method, $uri, array $headers = [], $body = []): ResponseContract
    {
        $method = strtoupper($method);
        $endpoint = $this->convertUriToEndpoint($uri);

        if ($method === 'GET' && ! $body instanceof StreamInterface) {
            $endpoint->addQuery($body);
            $body = [];
        }

        list($headers, $body) = $this->prepareRequestPayloads($headers, $body);

        return $this->requestWith(
            $method, $endpoint->get(), $headers, $body
        );
    }

    /**
     * Stream (multipart) the HTTP request.
     *
     * @param  string  $method
     * @param  \Laravie\Codex\Contracts\Endpoint|\Psr\Http\Message\UriInterface|string  $uri
     * @param  array  $headers
     * @param  \Psr\Http\Message\StreamInterface  $stream
     *
     * @return \Laravie\Codex\Contracts\Response
     */
    public function stream(string $method, $uri, array $headers = [], StreamInterface $stream): ResponseContract
    {
        list($headers, $stream) = $this->prepareRequestPayloads($headers, $stream);

        return $this->requestWith(
            strtoupper($method), $this->convertUriToEndpoint($uri)->get(), $headers, $stream
        );
    }

    /**
     * Stream (multipart) the HTTP request.
     *
     * @param  string  $method
     * @param  \Psr\Http\Message\UriInterface  $uri
     * @param  array  $headers
     * @param  \Psr\Http\Message\StreamInterface|array|null  $body
     *
     * @return \Laravie\Codex\Contracts\Response
     */
    protected function requestWith(string $method, UriInterface $uri, array $headers, $body): ResponseContract
    {
        if (in_array($method, ['HEAD', 'GET', 'TRACE'])) {
            $body = null;
        }

        $response = $this->responseWith(
            $this->http->send($method, $uri, $headers, $body)
        );

        $this->httpRequestQueries[] = compact('method', 'uri', 'headers', 'body', 'response');

        return $response;
    }

    /**
     * Prepare request payloads.
     *
     * @param  array  $headers
     * @param  mixed  $body
     *
     * @return array
     */
    protected function prepareRequestPayloads(array $headers = [], $body = []): array
    {
        $headers = $this->prepareRequestHeaders($headers);

        if ($body instanceof StreamInterface) {
            return [$headers, $body];
        }

        if (isset($headers['Content-Type']) && $headers['Content-Type'] == 'application/json') {
            $body = json_encode($body);
        } elseif (is_array($body)) {
            $body = http_build_query($body, null, '&');
        }

        return [$headers, $body];
    }

    /**
     * Convert URI to Endpoint object.
     *
     * @param  \Laravie\Codex\Contracts\Endpoint|\Psr\Http\Message\UriInterface|string  $uri
     *
     * @return \Laravie\Codex\Contracts\Endpoint
     */
    final protected function convertUriToEndpoint($uri): EndpointContract
    {
        if ($uri instanceof EndpointContract) {
            return $uri;
        } elseif ($uri instanceof UriInterface) {
            return new Endpoint($uri);
        }

        return new Endpoint(new Uri($uri));
    }

    /**
     * Resolve the responder class.
     *
     * @param  \Psr\Http\Message\ResponseInterface  $response
     *
     * @return \Laravie\Codex\Contracts\Response
     */
    abstract protected function responseWith(ResponseInterface $response): ResponseContract;

    /**
     * Prepare request headers.
     *
     * @param  array  $headers
     *
     * @return array
     */
    abstract protected function prepareRequestHeaders(array $headers = []): array;
}
