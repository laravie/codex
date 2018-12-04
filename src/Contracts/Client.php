<?php

namespace Laravie\Codex\Contracts;

use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\ResponseInterface;

interface Client
{
    /**
     * Use custom API Endpoint.
     *
     * @param  string  $endpoint
     *
     * @return $this
     */
    public function useCustomApiEndpoint(string $endpoint);

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
     * Get API endpoint URL.
     *
     * @return string
     */
    public function getApiEndpoint(): ?string;

    /**
     * Get API default version.
     *
     * @return string
     */
    public function getApiVersion(): string;

    /**
     * Send the HTTP request.
     *
     * @param  string  $method
     * @param  \Laravie\Codex\Contracts\Endpoint  $uri
     * @param  array  $headers
     * @param  \Psr\Http\Message\StreamInterface|\Laravie\Codex\Payload|array|null  $body
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function send(string $method, Endpoint $uri, array $headers = [], $body = []): ResponseInterface;

    /**
     * Stream (multipart) the HTTP request.
     *
     * @param  string  $method
     * @param  \Laravie\Codex\Contracts\Endpoint  $uri
     * @param  array  $headers
     * @param  \Psr\Http\Message\StreamInterface  $stream
     * @param  array  $files
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function stream(string $method, Endpoint $uri, array $headers = [], StreamInterface $stream): ResponseInterface;
}
