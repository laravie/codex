<?php

namespace Laravie\Codex\Concerns\Request;

use Laravie\Codex\Contracts\Endpoint;
use Laravie\Codex\Contracts\Response;
use Http\Discovery\StreamFactoryDiscovery;
use Http\Message\MultipartStream\MultipartStreamBuilder;

trait Multipart
{
    /**
     * Stream (multipart) the HTTP request.
     *
     * @param  string  $method
     * @param  \Laravie\Codex\Contracts\Endpoint|string  $path
     * @param  array  $headers
     * @param  \Psr\Http\Message\StreamInterface|array|null  $body
     * @param  array  $files
     *
     * @return \Laravie\Codex\Contracts\Response
     */
    public function stream(string $method, $path, array $headers = [], $body = [], array $files = []): Response
    {
        $body = $this->sanitizeFrom($body);

        list($headers, $stream) = $this->prepareMultipartRequestPayloads(
            $headers, $body, $files
        );

        $endpoint = ($path instanceof Endpoint)
                        ? $this->getApiEndpoint($path->getPath())->addQuery($path->getQuery())
                        : $this->getApiEndpoint($path);

        return $this->client->stream($method, $endpoint, $headers, $stream)
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
    final public function prepareMultipartRequestPayloads(array $headers = [], array $body = [], array $files = []): array
    {
        $multipart = (($headers['Content-Type'] ?? null) == 'multipart/form-data');

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
    final protected function addBodyToMultipartBuilder(MultipartStreamBuilder $builder, array $body, ?string $prefix = null): void
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
    final protected function addFilesToMultipartBuilder(MultipartStreamBuilder $builder, array $files = []): void
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
    abstract public function sanitizeTo($content): array;
}
