<?php

namespace Laravie\Codex\Support;

trait Resources
{
    /**
     * Send API request using GET.
     *
     * @param  string  $path
     * @param  array  $headers
     * @param  \Psr\Http\Message\StreamInterface|array|null  $body
     *
     * @return \Laravie\Codex\Reponse
     */
    protected function get($path, array $headers = [], $body = [])
    {
        return $this->send('GET', $path, $headers, $body);
    }

    /**
     * Send API request using POST.
     *
     * @param  string  $path
     * @param  array  $headers
     * @param  \Psr\Http\Message\StreamInterface|array|null  $body
     *
     * @return \Laravie\Codex\Reponse
     */
    protected function post($path, array $headers = [], $body = [])
    {
        return $this->send('POST', $path, $headers, $body);
    }

    /**
     * Send API request using PUT.
     *
     * @param  string  $path
     * @param  array  $headers
     * @param  \Psr\Http\Message\StreamInterface|array|null  $body
     *
     * @return \Laravie\Codex\Reponse
     */
    protected function put($path, array $headers = [], $body = [])
    {
        return $this->send('PUT', $path, $headers, $body);
    }

    /**
     * Send API request using PATCH.
     *
     * @param  string  $path
     * @param  array  $headers
     * @param  \Psr\Http\Message\StreamInterface|array|null  $body
     *
     * @return \Laravie\Codex\Reponse
     */
    protected function patch($path, array $headers = [], $body = [])
    {
        return $this->send('PATCH', $path, $headers, $body);
    }

    /**
     * Send API request using DELETE.
     *
     * @param  string  $path
     * @param  array  $headers
     * @param  \Psr\Http\Message\StreamInterface|array|null  $body
     *
     * @return \Laravie\Codex\Reponse
     */
    protected function delete($path, array $headers = [], $body = [])
    {
        return $this->send('DELETE', $path, $headers, $body);
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
