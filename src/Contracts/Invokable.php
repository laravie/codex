<?php

namespace Laravie\Codex\Contracts;

use Psr\Http\Message\UriInterface;

interface Invokable
{
    /**
     * Handle the request directly.
     *
     * @return \Laravie\Codex\Contracts\Response
     */
    public function handle(): Response;
}
