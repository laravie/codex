<?php

namespace Laravie\Codex;

use InvalidArgumentException;
use Psr\Http\Message\ResponseInterface;

abstract class Client implements Contracts\Client
{
    use Support\HttpClient;

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
            throw new InvalidArgumentException("API version [{$version}] is not supported.");
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
    public function uses($service, $version = null)
    {
        if (is_null($version) || ! array_key_exists($version, $this->supportedVersions)) {
            $version = $this->defaultVersion;
        }

        $name = str_replace('.', '\\', $service);
        $class = sprintf('%s\%s\%s', $this->getResourceNamespace(), $this->supportedVersions[$version], $name);

        if (! class_exists($class)) {
            throw new InvalidArgumentException("Resource [{$service}] for version [{$version}] is not available.");
        }

        return $this->via(function ($client) use ($class) {
            return new $class($client);
        });
    }

    /**
     * Handle uses using via.
     *
     * @param  callable  $callable
     *
     * @return \Laravie\Codex\Request
     */
    public function via($callable)
    {
        $request = call_user_func($callable, $this);

        if (! $request instanceof Request) {
            throw new InvalidArgumentException("Expected resource to be an instance of Laravie\Codex\Request.");
        }

        return $request;
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
        return $this->uses($service, $version);
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
     * Get resource default namespace.
     *
     * @return string
     */
    abstract protected function getResourceNamespace();
}
