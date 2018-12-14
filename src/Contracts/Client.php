<?php

namespace Laravie\Codex\Contracts;

use Psr\Http\Message\StreamInterface;

interface Client
{
    /**
     * Send the HTTP request.
     *
     * @param  string  $method
     * @param  \Laravie\Codex\Contracts\Endpoint  $uri
     * @param  array  $headers
     * @param  \Psr\Http\Message\StreamInterface|\Laravie\Codex\Payload|array|null  $body
     *
     * @return \Laravie\Codex\Contracts\Response
     */
    public function send(string $method, Endpoint $uri, array $headers = [], $body = []): Response;

    /**
     * Stream (multipart) the HTTP request.
     *
     * @param  string  $method
     * @param  \Laravie\Codex\Contracts\Endpoint  $uri
     * @param  array  $headers
     * @param  \Psr\Http\Message\StreamInterface  $stream
     * @param  array  $files
     *
     * @return \Laravie\Codex\Contracts\Response
     */
    public function stream(string $method, Endpoint $uri, array $headers = [], StreamInterface $stream): Response;
}
