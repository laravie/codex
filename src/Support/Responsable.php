<?php

namespace Laravie\Codex\Support;

use Psr\Http\Message\ResponseInterface;
use Laravie\Codex\Contracts\Response as ResponseContract;

trait Responsable
{
    /**
     * Interacts with Response.
     *
     * @param  \Laravie\Codex\Contracts\Response $response
     *
     * @return \Laravie\Codex\Contracts\Response
     */
    protected function interactsWithResponse(ResponseContract $response): ResponseContract
    {
        $response->setSanitizer($this->getSanitizer());

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
    abstract protected function responseWith(ResponseInterface $message): ResponseContract;
}
