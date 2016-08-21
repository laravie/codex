<?php

namespace Laravie\Codex\Support;

use Http\Discovery\StreamFactoryDiscovery;
use Http\Message\MultipartStream\MultipartStreamBuilder;

trait MultipartRequest
{
    use WithSerializer;

    /**
     * Prepare multipart request payloads.
     *
     * @param  array  $headers
     * @param  array  $body
     * @param  array  $files
     *
     * @return array
     */
    public function prepareMultipartRequestPayloads(array $headers = [], array $body = [], array $files = [])
    {
        if (empty($files)) {
            return [$headers, $body];
        }

        if ($this->hasSanitizer()) {
            $body = $this->getSanitizer()->from($body);
        }

        $builder = new MultipartStreamBuilder(StreamFactoryDiscovery::find());

        $this->addBodyToMultipartBuilder($builder, $body);
        $this->addFilesToMultipartBuilder($builder, $files);

        $content = $builder->build();
        $boundary = $builder->getBoundary();

        $headers['Content-Type'] = "multipart/form-data; boundary={$boundary}";

        return [$headers, $content];
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
    protected function addBodyToMultipartBuilder(MultipartStreamBuilder $builder, array $body, $prefix = null)
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
    protected function addFilesToMultipartBuilder(MultipartStreamBuilder $builder, array $files = [])
    {
        foreach($files as $key => $file) {
            if (! is_null($file)) {
                $builder->addResource($key, fopen($file, 'r'));
            }
        }
    }
}
