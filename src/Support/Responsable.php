<?php

namespace Laravie\Codex\Support;

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
}
