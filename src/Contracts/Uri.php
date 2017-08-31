<?php

namespace Laravie\Codex\Contracts;

interface Uri
{
    /**
     * Add query string.
     *
     * @param string|array  $key
     * @param string  $value
     *
     * @return $this
     */
    public function addQuery($key, $value = null);

    /**
     * Get URI.
     *
     * @return string
     */
    public function getUri();

    /**
     * Get path(s).
     *
     * @return array
     */
    public function getPath();

    /**
     * Get query string(s).
     *
     * @return array
     */
    public function getQuery();
}
