<?php

namespace Laravie\Codex\Support;

use Laravie\Codex\Endpoint;
use Http\Discovery\StreamFactoryDiscovery;
use Http\Message\MultipartStream\MultipartStreamBuilder;

trait MultipartRequest
{
    /**
     * Stream (multipart) the HTTP request.
     *
     * @param  string  $method
     * @param  \Laravie\Codex\Endpoint|string  $path
     * @param  array  $headers
     * @param  \Psr\Http\Message\StreamInterface|array|null  $body
     * @param  array  $files
     *
     * @return \Laravie\Codex\Contracts\Response
     */
    public function stream($method, $path, array $headers = [], $body = [], array $files = [])
    {
        $body = $this->sanitizeFrom($body);

        list($headers, $stream) = $this->prepareMultipartRequestPayloads(
            $headers, $body, $files
        );

        return $this->client->stream($method, $path, $headers, $stream)
                    ->setSanitizer($this->getSanitizer())
                    ->validate();
    }

    /**
     * Prepare multipart request payloads.
     *
     * @param  array  $headers
     * @param  array  $body
     * @param  array  $files
     *
     * @return array
     */
    final public function prepareMultipartRequestPayloads(array $headers = [], array $body = [], array $files = [])
    {
        $multipart = isset($headers['Content-Type']) && $headers['Content-Type'] == 'multipart/form-data';

        if (empty($files) && ! $multipart) {
            return [$headers, $body];
        }

        $builder = new MultipartStreamBuilder(StreamFactoryDiscovery::find());

        $this->addBodyToMultipartBuilder($builder, $this->sanitizeFrom($body));
        $this->addFilesToMultipartBuilder($builder, $files);

        $headers['Content-Type'] = 'multipart/form-data; boundary='.$builder->getBoundary();

        return [$headers, $builder->build()];
    }

    /**
     * Add body to multipart stream builder.
     *
     * @param  \Http\Message\MultipartStream\MultipartStreamBuilder  $builder
     * @param array  $body
     * @param string|null  $prefix
     *
     * @return void
     */
    final protected function addBodyToMultipartBuilder(MultipartStreamBuilder $builder, array $body, $prefix = null)
    {
        foreach ($body as $key => $value) {
            $name = $key;

            if (! is_null($prefix)) {
                $name = "{$prefix}[{$key}]";
            }

            if (is_array($value)) {
                $this->addBodyToMultipartBuilder($builder, $value, $name);
                continue;
            }

            $builder->addResource($name, $value, ['Content-Type' => 'text/plain']);
        }
    }

    /**
     * Add files to multipart stream builder.
     *
     * @param  \Http\Message\MultipartStream\MultipartStreamBuilder  $builder
     * @param  array  $files
     *
     * @return void
     */
    final protected function addFilesToMultipartBuilder(MultipartStreamBuilder $builder, array $files = [])
    {
        foreach ($files as $key => $file) {
            if (! is_null($file)) {
                $builder->addResource($key, fopen($file, 'r'));
            }
        }
    }

    /**
     * Sanitize "to" for content.
     *
     * @param  array|mixed  $content
     *
     * @return array
     */
    abstract public function sanitizeTo($content);
}
