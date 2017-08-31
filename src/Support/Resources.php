<?php

namespace Laravie\Codex\Support;

use Laravie\Codex\Contracts\Request;

trait Resources
{
    /**
     * Send API request using GET.
     *
     * @param  \Laravie\Codex\Contracts\Endpoint|string  $path
     * @param  array  $headers
     * @param  \Psr\Http\Message\StreamInterface|array|null  $body
     *
     * @return \Laravie\Codex\Reponse
     */
    protected function get($path, array $headers = [], $body = [])
    {
        return $this->send(Request::METHOD_GET, $path, $headers, $body);
    }

    /**
     * Send API request using POST.
     *
     * @param  \Laravie\Codex\Contracts\Endpoint|string  $path
     * @param  array  $headers
     * @param  \Psr\Http\Message\StreamInterface|array|null  $body
     *
     * @return \Laravie\Codex\Reponse
     */
    protected function post($path, array $headers = [], $body = [])
    {
        return $this->send(Request::METHOD_POST, $path, $headers, $body);
    }

    /**
     * Send API request using PUT.
     *
     * @param  \Laravie\Codex\Contracts\Endpoint|string  $path
     * @param  array  $headers
     * @param  \Psr\Http\Message\StreamInterface|array|null  $body
     *
     * @return \Laravie\Codex\Reponse
     */
    protected function put($path, array $headers = [], $body = [])
    {
        return $this->send(Request::METHOD_PUT, $path, $headers, $body);
    }

    /**
     * Send API request using PATCH.
     *
     * @param  \Laravie\Codex\Contracts\Endpoint|string  $path
     * @param  array  $headers
     * @param  \Psr\Http\Message\StreamInterface|array|null  $body
     *
     * @return \Laravie\Codex\Reponse
     */
    protected function patch($path, array $headers = [], $body = [])
    {
        return $this->send(Request::METHOD_PATCH, $path, $headers, $body);
    }

    /**
     * Send API request using DELETE.
     *
     * @param  \Laravie\Codex\Contracts\Endpoint|string  $path
     * @param  array  $headers
     * @param  \Psr\Http\Message\StreamInterface|array|null  $body
     *
     * @return \Laravie\Codex\Reponse
     */
    protected function delete($path, array $headers = [], $body = [])
    {
        return $this->send(Request::METHOD_DELETE, $path, $headers, $body);
    }

    /**
     * Send API request.
     *
     * @param  string  $method
     * @param  string  $path
     * @param  array  $headers
     * @param  \Psr\Http\Message\StreamInterface|array|null  $body
     *
     * @return \Laravie\Codex\Reponse
     */
    abstract protected function send($method, $path, array $headers = [], $body = []);
}
