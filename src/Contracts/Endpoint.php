<?php

namespace Laravie\Codex\Contracts;

interface Endpoint extends Uri
{
    /**
     * Get URI instance.
     *
     * @return \Psr\Http\Message\UriInterface
     */
    public function get();
}
