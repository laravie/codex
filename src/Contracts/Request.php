<?php

namespace Laravie\Codex\Contracts;

interface Request
{
    /**
     * Constants for base HTTP Method.
     */
    const METHOD_GET = 'GET';
    const METHOD_POST = 'POST';
    const METHOD_PUT = 'PUT';
    const METHOD_PATCH = 'PATCH';
    const METHOD_DELETE = 'DELETE';

    /**
     * Get API version.
     *
     * @return string
     */
    public function getVersion();
}
