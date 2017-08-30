<?php

namespace Laravie\Codex;

use InvalidArgumentException;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\ResponseInterface;

abstract class Client implements Contracts\Client
{
    /**
     * Http Client instance.
     *
     * @var \Http\Client\Common\HttpMethodsClient
     */
    protected $http;

    /**
     * The API endpoint.
     *
     * @var string
     */
    protected $apiEndpoint;

    /**
     * Default API version.
     *
     * @var string
     */
    protected $defaultVersion = 'v1';

    /**
     * List of supported API versions.
     *
     * @var array
     */
    protected $supportedVersions = [];

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

    /**
     * Use custom API Endpoint.
     *
     * @param  string  $endpoint
     *
     * @return $this
     */
    public function useCustomApiEndpoint($endpoint)
    {
        $this->apiEndpoint = $endpoint;

        return $this;
    }

    /**
     * Use different API version.
     *
     * @param  string  $version
     *
     * @throws \InvalidArgumentException
     *
     * @return $this
     */
    public function useVersion($version)
    {
        if (! array_key_exists($version, $this->supportedVersions)) {
            throw new InvalidArgumentException("API version {$version} is not supported.");
        }

        $this->defaultVersion = $version;

        return $this;
    }

    /**
     * Get API endpoint URL.
     *
     * @return string
     */
    public function getApiEndpoint()
    {
        return $this->apiEndpoint;
    }

    /**
     * Get API default version.
     *
     * @return string
     */
    public function getApiVersion()
    {
        return $this->defaultVersion;
    }

    /**
     * Get versioned resource (service).
     *
     * @param  string  $service
     * @param  string|null  $version
     *
     * @throws \InvalidArgumentException
     *
     * @return object
     */
    public function resource($service, $version = null)
    {
        if (is_null($version) || ! array_key_exists($version, $this->supportedVersions)) {
            $version = $this->defaultVersion;
        }

        $name = str_replace('.', '\\', $service);
        $class = sprintf('%s\%s\%s', $this->getResourceNamespace(), $this->supportedVersions[$version], $name);

        if (! class_exists($class)) {
            throw new InvalidArgumentException("Resource [{$service}] for version [{$version}] is not available");
        }

        return new $class($this);
    }

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

        return $this->responseWith(
            $this->http->send($method, $uri, $headers, $body)
        )->validate();
    }

    /**
     * Resolve the responder class.
     *
     * @param  \Psr\Http\Message\ResponseInterface  $response
     *
     * @return \Laravie\Codex\Contracts\Response
     */
    protected function responseWith(ResponseInterface $response)
    {
        return new Response($response);
    }

    /**
     * Prepare request headers.
     *
     * @param  array  $headers
     *
     * @return array
     */
    protected function prepareRequestHeaders(array $headers = [])
    {
        return $headers;
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
     * Get resource default namespace.
     *
     * @return string
     */
    abstract protected function getResourceNamespace();
}
