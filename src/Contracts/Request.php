<?php

namespace Laravie\Codex\Contracts;

interface Request
{
    /**
     * Constants for base HTTP Method.
     */
    const GET_HTTP = 'GET';
    const POST_HTTP = 'POST';
    const PUT_HTTP = 'PUT';
    const PATCH_HTTP = 'PATCH';
    const DELETE_HTTP = 'DELETE';

    /**
     * Get API version.
     *
     * @return string
     */
    public function getVersion();
}
