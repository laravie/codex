<?php

namespace Laravie\Codex\Support;

use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\ResponseInterface;

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
     * @param  \Psr\Http\Message\UriInterface|string  $uri
     * @param  array  $headers
     * @param  \Psr\Http\Message\StreamInterface|array|null  $body
     *
     * @return \Laravie\Codex\Contracts\Response
     */
    public function send($method, $uri, array $headers = [], $body = [])
    {
        list($headers, $body) = $this->prepareRequestPayloads($headers, $body);

        $method = strtoupper($method);

        return $this->responseWith(
            $this->http->send($method, $uri, $headers, $body)
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
