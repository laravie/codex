<?php

namespace Laravie\Codex\Contracts\Client;

interface Versioning
{
    /**
     * Use different API version.
     *
     * @param  string  $version
     *
     * @throws \InvalidArgumentException
     *
     * @return $this
     */
    public function useVersion(string $version);

    /**
     * Get API default version.
     *
     * @return string
     */
    public function getApiVersion(): string;
}
