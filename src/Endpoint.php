<?php

namespace Laravie\Codex;

use GuzzleHttp\Psr7\Uri;

class Endpoint implements Contracts\Endpoint
{
    /**
     * Base URL.
     *
     * @var string
     */
    protected $uri;

    /**
     * Request Path Endpoint.
     *
     * @var string
     */
    protected $path;

    /**
     * Request query strings.
     *
     * @var array
     */
    protected $query = [];

    /**
     * Construct API Endpoint.
     *
     * @param string  $uri
     * @param array|string  $path
     * @param array   $query
     */
    public function __construct($uri, $path = [], array $query = [])
    {
        $this->path = (array) $path;
        $this->query = $query;

        if (! is_null($uri)) {
            $this->uri = rtrim($uri, '/');
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
        return $this->uri;
    }

    /**
     * Get path(s).
     *
     * @return array
     */
    public function getPath()
    {
        return $this->path;
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
     * @return \GuzzleHttp\Psr7\Uri
     */
    public function get()
    {
        $query = http_build_query($this->getQuery(), null, '&');
        $to = implode('/', $this->getPath());

        return new Uri(sprintf('%s/%s?%s', $this->getUri(), $to, $query));
    }
}
