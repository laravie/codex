<?php

namespace Laravie\Codex\Support;

use Laravie\Codex\Contracts\Response;
use Laravie\Codex\Contracts\Filterable;
use Psr\Http\Message\ResponseInterface;

trait Responsable
{
    /**
     * Interacts with Response.
     *
     * @param  \Laravie\Codex\Contracts\Response $response
     *
     * @return \Laravie\Codex\Contracts\Response
     */
    protected function interactsWithResponse(Response $response): Response
    {
        if ($response instanceof Filterable && $this instanceof Filterable) {
            $response->setFilterable($this->getFilterable());
        }

        if ($this->validateResponseAutomatically === true) {
            $response->validate();
        }

        return $response;
    }

    /**
     * Resolve the responder class.
     *
     * @param  \Psr\Http\Message\ResponseInterface  $message
     *
     * @return \Laravie\Codex\Contracts\Response
     */
    abstract protected function responseWith(ResponseInterface $message): Response;
}
