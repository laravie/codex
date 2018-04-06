<?php

namespace Laravie\Codex\Support;

use Laravie\Codex\Contracts\Response;

trait JsonRequest
{
    /**
     * Send API request.
     *
     * @param  string  $method
     * @param  \Laravie\Codex\Contracts\Endpoint|string  $path
     * @param  array  $headers
     * @param  \Psr\Http\Message\StreamInterface|array|null  $body
     *
     * @return \Laravie\Codex\Contracts\Response
     */
    protected function sendJson(string $method, $path, array $headers = [], $body = []): Response
    {
        $headers['Content-Type'] = 'application/json';

        return $this->send($method, $path, $headers, $body);
    }

    /**
     * Send API request.
     *
     * @param  string  $method
     * @param  \Laravie\Codex\Contracts\Endpoint|string  $path
     * @param  array  $headers
     * @param  \Psr\Http\Message\StreamInterface|array|null  $body
     *
     * @return \Laravie\Codex\Contracts\Response
     */
    abstract protected function send(string $method, $path, array $headers = [], $body = []): Response;
}
