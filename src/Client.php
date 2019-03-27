<?php

namespace Laravie\Codex;

use InvalidArgumentException;
use Laravie\Codex\Contracts\Client as ClientContract;
use Laravie\Codex\Contracts\Request as RequestContract;

abstract class Client implements ClientContract
{
    use Common\HttpClient;

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
     * Dump HTTP requests for the client.
     *
     * @return array
     */
    public function queries(): array
    {
        return $this->httpRequestQueries;
    }

    /**
     * Use custom API Endpoint.
     *
     * @param  string  $endpoint
     *
     * @return $this
     */
    public function useCustomApiEndpoint(string $endpoint)
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
    public function useVersion(string $version)
    {
        if (! \array_key_exists($version, $this->supportedVersions)) {
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
    public function getApiEndpoint(): ?string
    {
        return $this->apiEndpoint;
    }

    /**
     * Get API default version.
     *
     * @return string
     */
    public function getApiVersion(): string
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
     * @return \Laravie\Codex\Contracts\Request
     */
    public function uses(string $service, ?string $version = null): RequestContract
    {
        if (\is_null($version) || ! \array_key_exists($version, $this->supportedVersions)) {
            $version = $this->defaultVersion;
        }

        $name = \str_replace('.', '\\', $service);
        $class = \sprintf('%s\%s\%s', $this->getResourceNamespace(), $this->supportedVersions[$version], $name);

        if (! \class_exists($class)) {
            throw new InvalidArgumentException("Resource [{$service}] for version [{$version}] is not available.");
        }

        return $this->via(new $class($this));
    }

    /**
     * Handle uses using via.
     *
     * @param  \Laravie\Codex\Contracts\Request  $request
     *
     * @return \Laravie\Codex\Contracts\Request
     */
    public function via(RequestContract $request): RequestContract
    {
        $request->setClient($this);

        return $request;
    }

    /**
     * Prepare request headers.
     *
     * @param  array  $headers
     *
     * @return array
     */
    protected function prepareRequestHeaders(array $headers = []): array
    {
        return $headers;
    }

    /**
     * Get resource default namespace.
     *
     * @return string
     */
    abstract protected function getResourceNamespace(): string;
}
