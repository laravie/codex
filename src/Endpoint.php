<?php

namespace Laravie\Codex;

use GuzzleHttp\Psr7\Uri;

class Endpoint
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
     * @param string  $path
     * @param array   $query
     */
    public function __construct($uri, $path, array $query = [])
    {
        $this->uri = $uri;
        $this->path = $path;
        $this->query = $query;
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
     * Get URI instance.
     *
     * @return \GuzzleHttp\Psr7\Uri
     */
    public function get()
    {
        $query = http_build_query($this->query, null, '&');

        return new Uri(sprintf('%s/%s?%s', $this->uri, $this->path, $query));
    }
}
