<?php

namespace Laravie\Codex\Contracts;

use Psr\Http\Message\UriInterface;

interface Endpoint
{
    /**
     * Add query string.
     *
     * @param string|array  $key
     * @param string  $value
     *
     * @return $this
     */
    public function addQuery($key, string $value = null);

    /**
     * Get URI.
     *
     * @return string|null
     */
    public function getUri(): ?string;

    /**
     * Get path(s).
     *
     * @return array
     */
    public function getPath(): array;

    /**
     * Get query string(s).
     *
     * @return array
     */
    public function getQuery(): array;

    /**
     * Get URI instance.
     *
     * @return \Psr\Http\Message\UriInterface
     */
    public function get(): UriInterface;
}
