<?php

namespace Laravie\Codex\Support;

use GuzzleHttp\Psr7\Uri;
use Laravie\Codex\Endpoint;
use Psr\Http\Message\UriInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\ResponseInterface;
use Laravie\Codex\Contracts\Endpoint as EndpointContract;

trait Request
{
    /**
     * Http Client instance.
     *
     * @var \Http\Client\Common\HttpMethodsClient
     */
    protected $http;

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
    public function send($method, $uri, array $headers = [], $body = [])
    {
        $method = strtoupper($method);
        $endpoint = $this->convertUriToEndpoint($uri);

        if ($method === 'GET' && ! $body instanceof StreamInterface) {
            $endpoint->addQuery($body);
            $body = [];
        }

        list($headers, $body) = $this->prepareRequestPayloads($headers, $body);

        return $this->responseWith(
            $this->http->send($method, $endpoint->get(), $headers, $body)
        );
    }

    /**
     * Prepare request payloads.
     *
     * @param  array  $headers
     * @param  mixed  $body
     *
     * @return array
     */
    protected function prepareRequestPayloads(array $headers = [], $body = [])
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
    protected function convertUriToEndpoint($uri)
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
    abstract protected function responseWith(ResponseInterface $response);

    /**
     * Prepare request headers.
     *
     * @param  array  $headers
     *
     * @return array
     */
    abstract protected function prepareRequestHeaders(array $headers = []);
}
