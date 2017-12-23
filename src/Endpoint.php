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
     * @param array|string  $path
     * @param array  $query
     */
    public function __construct($uri, $path = [], array $query = [])
    {
        if ($uri instanceof UriInterface) {
            $this->createFromUri($uri);
        } else {
            $uri = new Uri(sprintf('%s/%s', rtrim($uri, '/'), implode('/', (array) $path)));
        }

        $this->addQuery($query);
        $this->uri = $uri;
    }

    /**
     * Create from UriInterface.
     *
     * @param \Psr\Http\Message\UriInterface  $uri
     *
     * @return void
     */
    protected function createFromUri(UriInterface $uri): void
    {
        $this->createQuery($uri->getQuery());
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
     * @param string  $value
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
        return $this->uri->withQuery(
            http_build_query($this->getQuery(), null, '&')
        );
    }

    /**
     * Call method under \Psr\Http\Message\UriInterface.
     *
     * @param  string  $method
     * @param  array  $parameters
     *
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        if (! method_exists($this->uri, $method)) {
            throw new BadMethodCallException("Method [{$method}] doesn't exists.");
        }

        return $this->uri->{$method}(...$parameters);
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
