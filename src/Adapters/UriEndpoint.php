<?php

namespace Laravie\Codex\Adapters;

use Psr\Http\Message\UriInterface;
use Laravie\Codex\Contracts\Endpoint as EndpointContract;

class UriEndpoint implements EndpointContract
{
    /**
     * URL implementations.
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
     * Construct a new Endpoint Adapter.
     *
     * @param \Psr\Http\Message\UriInterface $uri
     */
    public function __construct(UriInterface $uri)
    {
        $this->uri = $uri;

        $this->createFromUri($uri);
    }

    /**
     * Create from UriInterface.
     *
     * @param \Psr\Http\Message\UriInterface  $uri
     *
     * @return void
     */
    protected function createFromUri(UriInterface $uri)
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
    protected function createQuery($query)
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
    public function addQuery($key, $value = null)
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
     * @return string
     */
    public function getUri()
    {
        if (! empty($this->uri->getHost())) {
            return $this->uri->getScheme().'://'.$this->uri->getHost();
        }
    }

    /**
     * Get path(s).
     *
     * @return array
     */
    public function getPath()
    {
        $path = trim($this->uri->getPath(), '/');

        return explode('/', $path);
    }

    /**
     * Get query string(s).
     *
     * @return array
     */
    public function getQuery()
    {
        return $this->query;
    }

    /**
     * Get URI instance.
     *
     * @return \Psr\Http\Message\UriInterface
     */
    public function get()
    {
        $this->uri->withQuery(http_build_query($this->getQuery(), null, '&'));

        return $this->uri;
    }
}
