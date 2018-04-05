<?php

namespace Laravie\Codex;

use GuzzleHttp\Psr7\Uri;
use BadMethodCallException;
use Psr\Http\Message\UriInterface;

class Endpoint implements Contracts\Endpoint
{
    /**
     * Base URL.
     *
     * @var \Psr\Http\Message\UriInterface
     */
    protected $uri;

    /**
     * Request query strings.
     *
     * @var array
     */
    protected $query = [];

    /**
     * Construct API Endpoint.
     *
     * @param \Psr\Http\Message\UriInterface|string  $uri
     * @param array|string  $paths
     * @param array  $query
     */
    public function __construct($uri, $paths = [], array $query = [])
    {
        $paths = is_null($paths) || $paths === '/' ? [] : $paths;

        $this->uri = $uri instanceof UriInterface
                        ? $uri
                        : $this->createUri($uri, (array) $paths);

        $this->createQueryFromUri($this->uri);
        $this->addQuery($query);
    }

    /**
     * Create instance of Uri.
     *
     * @param  string|null  $url
     * @param  array  $paths
     *
     * @return \Psr\Http\Message\UriInterface
     */
    final protected function createUri(?string $url, array $paths): UriInterface
    {
        $path = implode('/', $paths);

        if (! empty($path)) {
            $url = rtrim($url, '/')."/{$path}";
        }

        return new Uri($url);
    }

    /**
     * Create from UriInterface.
     *
     * @param \Psr\Http\Message\UriInterface  $uri
     *
     * @return void
     */
    final protected function createQueryFromUri(UriInterface $uri): void
    {
        $this->createQuery(trim($uri->getQuery(), '/'));
    }

    /**
     * Prepare query string.
     *
     * @param  string  $query
     *
     * @return void
     */
    protected function createQuery(string $query): void
    {
        if (empty($query)) {
            return;
        }

        foreach (explode('&', $query) as $pair) {
            if (strpos($pair, '=') >= 0) {
                list($key, $value) = explode('=', $pair, 2);

                $this->addQuery($key, urldecode($value));
            }
        }
    }

    /**
     * Add query string.
     *
     * @param string|array  $key
     * @param string|null  $value
     *
     * @return $this
     */
    public function addQuery($key, string $value = null): self
    {
        if (is_array($key)) {
            foreach ($key as $name => $content) {
                $this->addQuery($name, $content);
            }
        } else {
            $this->query[$key] = $value;
        }

        return $this;
    }

    /**
     * Get URI.
     *
     * @return string|null
     */
    public function getUri(): ?string
    {
        return ! empty($this->uri->getHost())
                    ? $this->uri->getScheme().'://'.$this->uri->getHost()
                    : null;
    }

    /**
     * Get path(s).
     *
     * @return array
     */
    public function getPath(): array
    {
        $path = trim($this->uri->getPath(), '/');

        return explode('/', $path);
    }

    /**
     * Get query string(s).
     *
     * @return array
     */
    public function getQuery(): array
    {
        return $this->query;
    }

    /**
     * Get URI instance.
     *
     * @return \Psr\Http\Message\UriInterface
     */
    public function get(): UriInterface
    {
        $this->withQuery(
            http_build_query($this->getQuery(), null, '&')
        );

        return $this->uri;
    }

    /**
     * Call method under \Psr\Http\Message\UriInterface.
     *
     * @param  string  $method
     * @param  array  $parameters
     *
     * @return mixed
     */
    public function __call(string $method, array $parameters)
    {
        if (! method_exists($this->uri, $method)) {
            throw new BadMethodCallException("Method [{$method}] doesn't exists.");
        }

        $result = $this->uri->{$method}(...$parameters);

        if (strpos($method, 'with') !== 0) {
            return $result;
        } elseif ($result instanceof UriInterface) {
            $this->uri = $result;
        }

        return $this;
    }

    /**
     * Return the string representation as a URI reference.
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->get();
    }
}
